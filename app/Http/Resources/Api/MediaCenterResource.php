<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaCenterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'file' => $this->file ? asset('storage/' . $this->file) : null,
            'video_url' => $this->video_url,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
