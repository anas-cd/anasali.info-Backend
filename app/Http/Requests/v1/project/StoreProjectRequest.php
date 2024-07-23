<?php

namespace App\Http\Requests\v1\project;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $response = Gate::authorize("create", Project::class);

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
            "name" => ["required", "string"],
            "description" => ["required", "string"],
            "image" => ["required", "image", "mimes:jpeg,png,jpg,gif|max:2048"],
            "codeLink" => ["required", "url"],
            "demoLink" => ["required", "url"],
            "major" => ["required", "string"],
            "tags" => ["required", "array"],
        ];
    }
}
