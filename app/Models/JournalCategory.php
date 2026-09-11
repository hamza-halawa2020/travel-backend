<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalCategory extends Model
{
    protected $fillable = [
        'slug',
        'label',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(JournalArticle::class)->orderByDesc('id');
    }
}
