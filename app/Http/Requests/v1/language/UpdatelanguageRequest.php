<?php

namespace App\Http\Requests\v1\language;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UpdatelanguageRequest extends FormRequest
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
            $language = Auth::user()->languages()->where("id", $this->id)->firstOrFail();

        } catch (\Throwable $th) {
            return false;
        }

        $response = Gate::authorize('update', $language);

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
            "lang" => ["sometimes", "string"],
            "level" => ["sometimes", "string"]
        ];
    }
}
