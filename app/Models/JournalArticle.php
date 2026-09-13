<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JournalArticle extends Model
{
    protected $fillable = [
        'journal_category_id',
        'slug',
        'read_time',
        'title',
        'dek',
        'excerpt',
        'image',
        'alt',
        'author',
        'updated_label',
        'cta_title',
        'cta_body',
        'cta_label',
        'cta_href',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $article) {
            if (empty($article->slug)) {
                $article->slug = static::generateUniqueSlug($article->title);
            }
        });

        static::updating(function (self $article) {
            if ($article->isDirty('title') && ! $article->isDirty('slug')) {
                $article->slug = static::generateUniqueSlug($article->title, $article->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(JournalCategory::class, 'journal_category_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(JournalArticleSection::class)->orderByDesc('id');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(JournalArticleFaq::class)->orderByDesc('id');
    }

    /**
     * FAQs linked via the many-to-many pivot (new source of truth).
     */
    public function sharedFaqs(): BelongsToMany
    {
        return $this->belongsToMany(
            JournalArticleFaq::class,
            'journal_article_faq_pivot',
            'journal_article_id',
            'journal_article_faq_id',
        );
    }

    public function relatedArticles(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'journal_article_related',
            'journal_article_id',
            'related_journal_article_id',
        )->orderByDesc('journal_article_related.id');
    }

    public function summaryPayload(): array
    {
        return [
            'slug' => $this->slug,
            'category' => $this->category?->label ?? '',
            'title' => $this->title,
            'dek' => $this->dek,
            'excerpt' => $this->excerpt,
            'image' => $this->resolveImageUrl($this->image),
            'alt' => $this->alt,
        ];
    }

    public function fullPayload(): array
    {
        return [
            ...$this->summaryPayload(),
            'sections' => $this->sections->map->payload()->values()->all(),
            'faqs' => $this->sharedFaqs->map->payload()->values()->all(),
            'relatedArticles' => $this->relatedArticles->map->summaryPayload()->values()->all(),
        ];
    }

    private function resolveImageUrl(?string $path): ?string
    {
        if ($path === null) {
            return null;
        }

        // Already an absolute URL (e.g. Unsplash links stored directly)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $storagePath = parse_url($path, PHP_URL_PATH);

            return $storagePath && str_starts_with($storagePath, '/storage/')
                ? url($storagePath)
                : $path;
        }

        // Relative storage path — prefix with app URL
        if (str_starts_with($path, '/storage/')) {
            return url($path);
        }

        return url('/storage/'.ltrim($path, '/'));
    }
}
