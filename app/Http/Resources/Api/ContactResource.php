<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'age' => $this->age,
            'country' => $this->country,
            'course' => $this->course,
            'course_id' => $this->course_id,
            'pricing_plan_id' => $this->pricing_plan_id,
            'pricing_plan' => $this->pricingPlan?->title,
            'message' => $this->message,
        ];
    }
}