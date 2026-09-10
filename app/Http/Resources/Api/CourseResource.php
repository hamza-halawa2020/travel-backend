<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'course_category_id' => $this->course_category_id,
            'category' => $this->categoryRelation ? [
                'id' => $this->categoryRelation->id,
                'name' => $this->categoryRelation->name,
            ] : null,
            'description' => $this->description,
            'image_url' => $this->image ? asset('storage/' . $this->image) : null,
            'status' => $this->status,
        ];
    }
}
