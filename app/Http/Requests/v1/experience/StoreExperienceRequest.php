<?php

namespace App\Http\Requests\v1\experience;

use App\Models\Experience;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreExperienceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $response = Gate::authorize('create', Experience::class);

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
            "role" => ["required", "string"],
            "major" => ["required", "string"],
            "description" => ["required", "string"],
            "startDate" => ["required", "date_format:Y-m-d"],
            "endDate" => ["date_format:Y-m-d"],
            "employer" => ["required", "array"],
            "employer.name" => ["required", "string"],
            "employer.refName" => ["string"],
            "employer.refContact" => ["string"],
            "employer.image" => ["required", "image", "mimes:jpeg,png,jpg,gif|max:2048"],
            "employer.website" => ["required", "url"],
        ];
    }
}
