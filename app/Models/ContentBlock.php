<?php

namespace App\Models;

use App\Support\ApiContentCache;
use App\Support\ResolvesImageUrls;
use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    use ResolvesImageUrls;
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
     * Return the raw payload without resolving image URLs.
     * Use this when reading data to store elsewhere (e.g. seeders).
     */
    public static function rawPayloadFor(string $key): array
    {
        $block = self::query()->where('key', $key)->first();

        abort_unless($block, 404, "Content block [{$key}] not found.");

        return $block->payload ?? [];
    }

    /**
     * Recursively replace any image path with a fully-qualified public URL.
     */
    public static function resolveStorageUrls(mixed $value): mixed
    {
        if (is_string($value)) {
            return static::resolveImagePath($value) ?? $value;
        }

        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = static::resolveStorageUrls($v);
            }
        }

        return $value;
    }
}
