<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
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
            if (str_starts_with($value, '/storage/')) {
                return url($value);
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
}
