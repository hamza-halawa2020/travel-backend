<?php

namespace App\Models;

use App\Support\ApiContentCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JournalArticleFaq extends Model
{
    protected static function booted(): void
    {
        static::saved(fn () => ApiContentCache::bumpJournal());
        static::deleted(fn () => ApiContentCache::bumpJournal());
    }

    protected $fillable = [
        'journal_article_id',
        'question',
        'answer',
    ];

    /**
     * Legacy direct FK relationship (kept for backward compat / existing data).
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(JournalArticle::class, 'journal_article_id');
    }

    /**
     * Many-to-many: a FAQ can be attached to multiple articles via the pivot.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(
            JournalArticle::class,
            'journal_article_faq_pivot',
            'journal_article_faq_id',
            'journal_article_id',
        );
    }

    public function payload(): array
    {
        return [
            'question' => $this->question,
            'answer' => $this->answer,
        ];
    }
}
