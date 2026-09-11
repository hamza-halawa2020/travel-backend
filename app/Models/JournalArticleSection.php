<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalArticleSection extends Model
{
    protected $fillable = [
        'journal_article_id',
        'heading',
        'body',
        'image_src',
        'image_alt',
        'image_caption',
        'pull_quote',
        'bullets',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'body' => 'array',
            'bullets' => 'array',
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(JournalArticle::class, 'journal_article_id');
    }

    public function payload(): array
    {
        $payload = [
            'heading' => $this->heading,
            'body' => $this->body ?? [],
        ];

        if ($this->image_src) {
            $payload['image'] = [
                'src' => $this->image_src,
                'alt' => $this->image_alt ?? '',
                'caption' => $this->image_caption ?? '',
            ];
        }

        if ($this->pull_quote) {
            $payload['pullQuote'] = $this->pull_quote;
        }

        if (! empty($this->bullets)) {
            $payload['bullets'] = $this->bullets;
        }

        return $payload;
    }
}
