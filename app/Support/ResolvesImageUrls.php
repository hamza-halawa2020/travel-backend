<?php

namespace App\Support;

trait ResolvesImageUrls
{
    /**
     * Convert any stored image path to a fully-qualified public URL.
     *
     * Handles these input formats:
     *   • Already absolute HTTP/HTTPS URL          → returned as-is
     *   • Absolute URL pointing to /storage/...    → re-prefixed with app URL
     *   • /storage/...                             → prefixed with app URL
     *   • Bare image filename / relative path      → treated as storage-relative path
     */
    protected function resolveImageUrl(?string $path): ?string
    {
        return static::resolveImagePath($path);
    }

    public static function resolveImagePath(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        // Already an absolute URL
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            // Re-normalize in case the stored URL has a wrong origin
            $storagePath = parse_url($path, PHP_URL_PATH);
            if ($storagePath && str_starts_with($storagePath, '/storage/')) {
                return url($storagePath);
            }

            return $path;
        }

        // Absolute path starting with /storage/
        if (str_starts_with($path, '/storage/')) {
            return url($path);
        }

        // Relative path that looks like an image → assume it lives in storage
        if (static::looksLikeImagePath($path)) {
            return url('/storage/'.ltrim($path, '/'));
        }

        return $path;
    }

    private static function looksLikeImagePath(string $value): bool
    {
        if ($value === '' || str_starts_with($value, '#')) {
            return false;
        }

        if (str_starts_with($value, 'mailto:') || str_starts_with($value, 'tel:')) {
            return false;
        }

        return (bool) preg_match('/\.(avif|gif|jpe?g|png|webp|svg)$/i', $value);
    }
}
