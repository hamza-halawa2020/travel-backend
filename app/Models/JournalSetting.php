<?php

namespace App\Models;

use App\Support\ApiContentCache;
use Illuminate\Database\Eloquent\Model;

class JournalSetting extends Model
{
    protected static function booted(): void
    {
        static::saved(fn () => ApiContentCache::bumpJournal());
        static::deleted(fn () => ApiContentCache::bumpJournal());
    }

    protected $fillable = [
        'page_eyebrow',
        'page_title',
        'page_body',
        'filter_label',
        'default_category_slug',
    ];
}
