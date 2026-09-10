<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentAsset extends Model
{
    protected $fillable = [
        'original_url',
        'public_path',
        'source',
        'mime_type',
        'size',
        'checksum',
    ];
}
