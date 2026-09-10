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

        return $block->payload ?? [];
    }
}
