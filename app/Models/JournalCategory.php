<?php

namespace App\Models;

use App\Support\ApiContentCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class JournalCategory extends Model
{
    protected $fillable = [
        'slug',
        'label',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $category) {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->label);
            }
        });

        static::updating(function (self $category) {
            if ($category->isDirty('label') && ! $category->isDirty('slug')) {
                $category->slug = static::generateUniqueSlug($category->label, $category->id);
            }
        });

        static::saved(fn () => ApiContentCache::bumpJournal());
        static::deleted(fn () => ApiContentCache::bumpJournal());
    }

    protected static function generateUniqueSlug(string $label, ?int $ignoreId = null): string
    {
        $base = Str::slug($label);
        $slug = $base;
        $i = 1;

        while (
            static::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function articles(): HasMany
    {
        return $this->hasMany(JournalArticle::class)->orderByDesc('id');
    }
}
