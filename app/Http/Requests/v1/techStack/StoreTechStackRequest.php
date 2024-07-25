<?php

namespace App\Http\Requests\v1\techStack;

use App\Models\TechStack;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreTechStackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $response = Gate::authorize("create", TechStack::class);

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
            "major" => ["required", "string"],
            "label" => ["required", "string"],
            "category" => ["required", Rule::in(['tech', 'tool'])],
            "type" => ["nullable", Rule::in(["primary", "supportive"])],
            "link" => ["required", "url"],
            "tip" => ["nullable", "string"],
            "progress" => ["nullable", "numeric", "between:0,1"],
            "img" => ["required", "image", "mimes:jpeg,png,jpg,giv,svg|max:2048"]
        ];
    }
}
