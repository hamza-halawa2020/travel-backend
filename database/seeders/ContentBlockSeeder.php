<?php

namespace Database\Seeders;

use App\Models\ContentAsset;
use App\Models\ContentBlock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContentBlockSeeder extends Seeder
{
    /**
     * @var array<string, string>
     */
    private array $files = [
        'site-settings' => 'site-settings.json',
        'home-content' => 'home-content.json',
        'journal-content' => 'journal-content.json',
        'journal-articles' => 'journal-articles.json',
    ];

    private string $frontendPublicPath;

    public function run(): void
    {
        $this->frontendPublicPath = dirname(base_path()).DIRECTORY_SEPARATOR.'travel'.DIRECTORY_SEPARATOR.'public';

        foreach ($this->files as $key => $file) {
            $path = resource_path("data/{$file}");

            if (! File::exists($path)) {
                $this->command?->warn("Skipped missing content file: {$file}");
                continue;
            }

            $payload = json_decode(File::get($path), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException("Invalid JSON in {$file}: ".json_last_error_msg());
            }

            $payload = $this->localizeImageReferences($payload);

            ContentBlock::query()->updateOrCreate(
                ['key' => $key],
                ['payload' => $payload],
            );
        }
    }

    private function localizeImageReferences(mixed $value): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        foreach ($value as $key => $item) {
            if (
                is_string($item)
                && in_array($key, ['image', 'src', 'backgroundImage'], true)
                && $this->isImageReference($item)
            ) {
                $value[$key] = $this->storeAsset($item)->public_path;
                continue;
            }

            $value[$key] = $this->localizeImageReferences($item);
        }

        return $value;
    }

    private function isImageReference(string $value): bool
    {
        return Str::startsWith($value, ['http://', 'https://', '/assets/images/', 'assets/images/']);
    }

    private function storeAsset(string $reference): ContentAsset
    {
        $existing = ContentAsset::query()
            ->where('original_url', $reference)
            ->first();

        if ($existing && $this->storedAssetExists($existing->public_path)) {
            return $existing;
        }

        [$contents, $extension, $mimeType, $source] = Str::startsWith($reference, ['http://', 'https://'])
            ? $this->downloadRemoteAsset($reference)
            : $this->readFrontendAsset($reference);

        $filename = sha1($reference).'.'.$extension;
        $relativePath = "content-assets/{$filename}";

        Storage::disk('public')->put($relativePath, $contents);

        return ContentAsset::query()->updateOrCreate(
            ['original_url' => $reference],
            [
                'public_path' => "/storage/{$relativePath}",
                'source' => $source,
                'mime_type' => $mimeType,
                'size' => strlen($contents),
                'checksum' => hash('sha256', $contents),
            ],
        );
    }

    private function storedAssetExists(string $publicPath): bool
    {
        if (! Str::startsWith($publicPath, '/storage/')) {
            return false;
        }

        return Storage::disk('public')->exists(Str::after($publicPath, '/storage/'));
    }

    /**
     * @return array{0: string, 1: string, 2: string|null, 3: string}
     */
    private function readFrontendAsset(string $reference): array
    {
        $relativePath = ltrim($reference, '/');
        $path = $this->frontendPublicPath.DIRECTORY_SEPARATOR.str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);

        if (! File::exists($path)) {
            throw new \RuntimeException("Frontend asset not found: {$reference}");
        }

        return [
            File::get($path),
            $this->extensionFromPath($path, 'jpg'),
            File::mimeType($path) ?: null,
            'local',
        ];
    }

    /**
     * @return array{0: string, 1: string, 2: string|null, 3: string}
     */
    private function downloadRemoteAsset(string $url): array
    {
        $response = Http::timeout(60)
            ->retry(3, 500)
            ->withHeaders(['User-Agent' => 'Total Stay Tours content seeder'])
            ->get($url);

        if (! $response->successful()) {
            throw new \RuntimeException("Unable to download remote asset [{$url}]: HTTP {$response->status()}");
        }

        $mimeType = $response->header('Content-Type');

        return [
            $response->body(),
            $this->extensionFromMimeType($mimeType) ?? $this->extensionFromPath(parse_url($url, PHP_URL_PATH) ?: '', 'jpg'),
            $mimeType,
            'remote',
        ];
    }

    private function extensionFromPath(string $path, string $fallback): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return match ($extension) {
            'jpeg', 'jpg' => 'jpg',
            'png' => 'png',
            'webp' => 'webp',
            'gif' => 'gif',
            'jfif' => 'jpg',
            default => $fallback,
        };
    }

    private function extensionFromMimeType(?string $mimeType): ?string
    {
        $mimeType = strtolower((string) $mimeType);

        if (str_contains($mimeType, ';')) {
            $mimeType = trim(Str::before($mimeType, ';'));
        }

        return match ($mimeType) {
            'image/jpeg', 'image/jpg', 'image/jfif' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => null,
        };
    }
}
