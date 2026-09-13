<?php

namespace Database\Seeders;

use App\Models\ContentBlock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
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

    private string $seedAssetPath;

    public function run(): void
    {
        $this->seedAssetPath = database_path('seeders/assets/content-assets');

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

            $payload = $this->pruneUnusedPayload($key, $payload);
            $payload = $this->localizeImageReferences($payload);

            ContentBlock::query()->updateOrCreate(
                ['key' => $key],
                ['payload' => $payload],
            );
        }
    }

    private function pruneUnusedPayload(string $key, array $payload): array
    {
        if ($key === 'site-settings') {
            unset($payload['contact']['corporateEmail']);
        }

        if ($key === 'home-content') {
            unset(
                $payload['hero']['actions']['secondaryLabel'],
                $payload['sections']['services'],
                $payload['sections']['corporate'],
                $payload['sections']['journal']['articles'],
                $payload['clientSections'],
            );

            if (isset($payload['services']) && is_array($payload['services'])) {
                $payload['services'] = array_map(function (array $service): array {
                    unset($service['form'], $service['ctaLabel'], $service['reversed']);

                    return $service;
                }, $payload['services']);
            }

            if (isset($payload['destinations']) && is_array($payload['destinations'])) {
                $payload['destinations'] = array_map(function (array $destination): array {
                    unset($destination['description']);

                    return $destination;
                }, $payload['destinations']);
            }
        }

        return $payload;
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
                $value[$key] = $this->storeAsset($item);

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

    private function storeAsset(string $reference): string
    {
        $seedPath = $this->seedAssetFor($reference);
        $relativePath = 'content-assets/'.pathinfo($seedPath, PATHINFO_BASENAME);

        if (! Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->put($relativePath, File::get($seedPath));
        }

        return "/storage/{$relativePath}";
    }

    private function seedAssetFor(string $reference): string
    {
        $matches = glob($this->seedAssetPath.DIRECTORY_SEPARATOR.sha1($reference).'.*') ?: [];

        if ($matches !== []) {
            return $matches[0];
        }

        throw new \RuntimeException("Seed asset not found for [{$reference}] in {$this->seedAssetPath}");
    }
}
