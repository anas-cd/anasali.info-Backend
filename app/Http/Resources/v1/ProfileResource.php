<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "speciality" => $this->speciality,
            "email" => $this->email,
            "phone" => $this->phone,
            "description" => $this->biography,
            "social" => $this->social
        ];
    }
}
