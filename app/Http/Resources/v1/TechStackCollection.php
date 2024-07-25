<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TechStackCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $techPrimary = $this->collection->where("category", "tech")->where("type", "primary");
        $tachSupportive = $this->collection->where("category", "tech")->where("type", "supportive");
        $tools = $this->collection->where("category", "tool");

        return [
            "tech" => [
                "primary" => TechStackResource::collection($techPrimary),
                "supportive" => TechStackResource::collection($tachSupportive)
            ],
            "tools" => TechStackResource::collection($tools)
        ];
    }
}
