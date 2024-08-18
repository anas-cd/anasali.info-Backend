<?php

namespace App\Http\Requests\v1\interest;

use App\Models\Interest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreInterestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $response = Gate::authorize("create", Interest::class);

        return $response->allowed() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ["required", "string"],
            'subText' => ["string"],
            'description' => ["required", "string"],
            'link' => ["url"],
            'image' => ["image", "mimes:jpeg,png,jpg,gif|max:2048"],
            'major' => ["required", "string"]
        ];
    }
}
