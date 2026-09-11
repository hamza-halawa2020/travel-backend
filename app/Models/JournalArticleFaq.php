<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalArticleFaq extends Model
{
    protected $fillable = [
        'journal_article_id',
        'question',
        'answer',
        'sort_order',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(JournalArticle::class, 'journal_article_id');
    }

    public function payload(): array
    {
        return [
            'question' => $this->question,
            'answer' => $this->answer,
        ];
    }
}
