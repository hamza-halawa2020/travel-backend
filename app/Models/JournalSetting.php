<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalSetting extends Model
{
    protected $fillable = [
        'page_eyebrow',
        'page_title',
        'page_body',
        'filter_label',
        'default_category_slug',
    ];
}
