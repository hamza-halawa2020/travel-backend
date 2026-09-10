<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaCenter extends Model
{
    use HasFactory;

    protected $table = 'media_centers';

    protected $fillable = [
        'title',
        'type', //'image', 'video'
        'file',
        'video_url',
        'status',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
