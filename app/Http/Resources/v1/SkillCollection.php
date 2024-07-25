<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SkillCollection extends ResourceCollection
{
    /**
     * NOTE: this collection is defined for personal use only,
     *       as it doesn't show the id's of some skill types.
     *
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $primary = $this->collection->where('type', 'primary');
        $secondary = $this->collection->where('type', 'secondary')->pluck('label');
        $soft = $this->collection->where('type', 'soft')->pluck('label');

        return [
            'primary' => SkillResource::collection($primary),
            'supportive' => $secondary,
            'soft' => $soft
        ];
    }
}
