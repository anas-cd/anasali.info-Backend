<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
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
            "role" => $this->role,
            "description" => $this->description,
            "startDate" => $this->start_date,
            "endDate" => $this->end_date,
            "employer" => [
                "name" => $this->employer_name,
                "refName" => $this->employer_reference,
                "refContact" => $this->employer_reference_contact,
                "image" => asset($this->employer_image_path),
                "website" => $this->employer_website,
            ]
        ];
    }
}
