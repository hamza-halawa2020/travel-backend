<?php

namespace App\Models;

use App\Support\ApiContentCache;
use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    protected static function booted(): void
    {
        static::saved(fn () => ApiContentCache::bumpContent());
        static::deleted(fn () => ApiContentCache::bumpContent());
    }

    protected $fillable = [
        'key',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    public static function payloadFor(string $key): array
    {
        $block = self::query()->where('key', $key)->first();

        abort_unless($block, 404, "Content block [{$key}] not found.");

        return static::resolveStorageUrls($block->payload ?? []);
    }

    /**
     * Recursively replace relative /storage/... paths with absolute URLs
     * so cross-origin frontends can load images correctly.
     */
    public static function resolveStorageUrls(mixed $value): mixed
    {
        if (is_string($value)) {
            if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                $storagePath = parse_url($value, PHP_URL_PATH);

                return $storagePath && str_starts_with($storagePath, '/storage/')
                    ? url($storagePath)
                    : $value;
            }

            if (str_starts_with($value, '/storage/')) {
                return url($value);
            }

            if (static::isStoredImagePath($value)) {
                return url('/storage/'.ltrim($value, '/'));
            }

            return $value;
        }

        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = static::resolveStorageUrls($v);
            }
        }

        return $value;
    }

    private static function isStoredImagePath(string $value): bool
    {
        if ($value === '' || str_starts_with($value, '/') || str_starts_with($value, '#')) {
            return false;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return false;
        }

        if (str_starts_with($value, 'mailto:') || str_starts_with($value, 'tel:')) {
            return false;
        }

        return (bool) preg_match('/\.(avif|gif|jpe?g|png|webp)$/i', $value);
    }
}
