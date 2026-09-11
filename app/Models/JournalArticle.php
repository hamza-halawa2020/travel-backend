<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JournalCategory::class, 'journal_category_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(JournalArticleSection::class)->orderBy('sort_order');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(JournalArticleFaq::class)->orderBy('sort_order');
    }

    public function relatedArticles(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'journal_article_related',
            'journal_article_id',
            'related_journal_article_id',
        )->withPivot('sort_order')->orderByPivot('sort_order');
    }

    public function summaryPayload(): array
    {
        return [
            'slug' => $this->slug,
            'category' => $this->category?->label ?? '',
            'readTime' => $this->read_time,
            'title' => $this->title,
            'dek' => $this->dek,
            'excerpt' => $this->excerpt,
            'image' => $this->image,
            'alt' => $this->alt,
            'author' => $this->author,
            'updated' => $this->updated_label,
        ];
    }

    public function fullPayload(): array
    {
        return [
            ...$this->summaryPayload(),
            'cta' => [
                'title' => $this->cta_title ?? '',
                'body' => $this->cta_body ?? '',
                'label' => $this->cta_label ?? '',
                'href' => $this->cta_href ?? '',
            ],
            'sections' => $this->sections->map->payload()->values()->all(),
            'faqs' => $this->faqs->map->payload()->values()->all(),
            'relatedArticles' => $this->relatedArticles->map->summaryPayload()->values()->all(),
        ];
    }
}
