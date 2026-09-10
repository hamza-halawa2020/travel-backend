<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PricingPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'currency' => $this->currency,
            'price' => $this->price,
            'classes_count' => $this->classes_count,
            'days_per_week' => $this->days_per_week,
            'minutes_per_class' => $this->minutes_per_class,
            'price_per_class' => $this->price_per_class,
            'badge' => $this->badge,
            'features' => $this->features ?? [],
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ];
    }
}
