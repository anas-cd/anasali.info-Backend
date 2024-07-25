<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TechStackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "label" => $this->label,
            "img" => asset($this->image_path),
            "link" => $this->url,
            "tip" => $this->tip,
            "progress" => $this->progress
        ];
    }
}
