<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'course_category_id',
        'category',
        'description',
        'image',
        'status',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function categoryRelation()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
