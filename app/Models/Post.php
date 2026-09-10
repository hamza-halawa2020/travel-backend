<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'status',
        'created_by',
        'meta_title',
        'meta_description',
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            if ($post->isDirty('title') || blank($post->slug)) {
                $post->slug = static::makeUniqueSlug($post->title, $post->id);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public static function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = static::slugify($title);
        $slug = $baseSlug;
        $counter = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $suffix = '-' . $counter++;
            $slug = Str::limit($baseSlug, 255 - strlen($suffix), '') . $suffix;
        }

        return $slug;
    }

    private static function slugify(string $title): string
    {
        $slug = trim(preg_replace('/[^\pL\pN]+/u', '-', mb_strtolower($title)), '-');

        return Str::limit($slug ?: 'post', 255, '');
    }
}
