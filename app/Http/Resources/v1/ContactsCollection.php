<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContactsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $emails = $this->collection->where("type", "email")->pluck("contact");
        $phones = $this->collection->where("type", "phone")->pluck("contact");

        return [
            "emails" => $emails,
            "phones" => $phones
        ];
    }
}
