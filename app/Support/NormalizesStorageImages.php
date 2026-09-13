<?php

namespace App\Support;

trait NormalizesStorageImages
{
    /**
     * Strip the /storage/ prefix from a stored path so Filament's FileUpload
     * can locate the file on the public disk (e.g. "/storage/hero/x.jpg" → "hero/x.jpg").
     */
    public static function normalizeImageForUpload(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        // Full URL — extract just the storage-relative part
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $urlPath = parse_url($path, PHP_URL_PATH) ?? '';
            $path = $urlPath;
        }

        if (str_starts_with($path, '/storage/')) {
            return ltrim(substr($path, strlen('/storage/')), '/');
        }

        return $path;
    }

    /**
     * Apply normalizeImageForUpload to a key inside every item of an array.
     *
     * @param  array<int, array<string, mixed>>  $items
     */
    public static function normalizeImagesInCollection(array $items, string $key = 'image'): array
    {
        return array_map(function (array $item) use ($key): array {
            $item[$key] = static::normalizeImageForUpload($item[$key] ?? null);
            return $item;
        }, $items);
    }

    // Instance method aliases for use in Page classes
    protected function normalizeImageForUploadInstance(?string $path): ?string
    {
        return static::normalizeImageForUpload($path);
    }

    protected function normalizeImagesInCollectionInstance(array $items, string $key = 'image'): array
    {
        return static::normalizeImagesInCollection($items, $key);
    }
}
