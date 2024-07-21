<?php

namespace App\Http\Requests\v1\education;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UpdateEducationRequest extends FormRequest
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
            $education = Auth::user()->educations()->where("major", $this->major)->firstOrFail();

        } catch (\Throwable $th) {
            return false;
        }

        $response = Gate::authorize('update', $education);

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
            'name' => ['sometimes', 'string'],
            'source' => ['sometimes', 'string'],
            'country' => ['sometimes', 'string'],
            'date' => ['sometimes', 'date_format:Y-m-d'],
            'courses' => ['sometimes']
        ];
    }
}
