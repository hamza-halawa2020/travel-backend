<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'status',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'course_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
