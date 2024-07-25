<?php

namespace App\Http\Requests\v1\techStack;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateTechStackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /**
         * TODO: query profile from a route service provider to be passed and used in controller, request, and policy for better performance.
         * TODO: configure a better error handling in case the profile is not found.
         */
        try {
            $tech = Auth::user()->techStacks()->where("id", $this->id)->firstOrFail();

        } catch (\Throwable $th) {
            return false;
        }

        $response = Gate::authorize('update', $tech);

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
            "label" => ["sometimes", "string"],
            "category" => ["sometimes", Rule::in(['tech', 'tool'])],
            "type" => ["sometimes", Rule::in(["primary", "supportive"])],
            "link" => ["sometimes", "url"],
            "tip" => ["sometimes", "string"],
            "progress" => ["sometimes", "numeric", "between:0,1"],
            "img" => ["sometimes", "image", "mimes:jpeg,png,jpg,giv,svg|max:2048"]
        ];
    }
}
