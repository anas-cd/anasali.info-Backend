<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sub_text' => $this->sub_text,
            'description' => $this->description,
            'link' => $this->url,
            'image' => $this->image_path ? asset($this->image_path) : null,
        ];
    }
}
