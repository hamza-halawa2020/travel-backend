<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalCategory extends Model
{
    protected $fillable = [
        'slug',
        'label',
        'sort_order',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(JournalArticle::class)->orderBy('sort_order');
    }
}
