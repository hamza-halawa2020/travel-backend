<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class ApiContentCache
{
    private const CONTENT_VERSION_KEY = 'api-content:content-version';

    private const JOURNAL_VERSION_KEY = 'api-content:journal-version';

    public static function contentKey(string $name): string
    {
        return 'api-content:content:'.static::contentVersion().':'.$name;
    }

    public static function journalKey(string $name): string
    {
        return 'api-content:journal:'.static::journalVersion().':'.$name;
    }

    public static function bumpContent(): void
    {
        static::bump(static::CONTENT_VERSION_KEY);
    }

    public static function bumpJournal(): void
    {
        static::bump(static::JOURNAL_VERSION_KEY);
    }

    private static function contentVersion(): int
    {
        return static::version(static::CONTENT_VERSION_KEY);
    }

    private static function journalVersion(): int
    {
        return static::version(static::JOURNAL_VERSION_KEY);
    }

    private static function version(string $key): int
    {
        if (! Cache::has($key)) {
            Cache::forever($key, 1);
        }

        return (int) Cache::get($key, 1);
    }

    private static function bump(string $key): void
    {
        if (! Cache::has($key)) {
            Cache::forever($key, 1);
        }

        Cache::increment($key);
    }
}
