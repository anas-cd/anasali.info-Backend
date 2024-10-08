<?php

namespace App\Http\Requests\v1\profile;

use App\Traits\APIResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UpdateprofileRequest extends FormRequest
{
    use APIResponseTrait;

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
            $profile = Auth::user()->profiles()->where("major", $this->major)->firstOrFail();

        } catch (\Throwable $th) {
            return false;
        }

        $response = Gate::authorize('update', $profile);

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
            'email' => ['sometimes', 'string', 'email'],
            'speciality' => ['sometimes', 'string'],
            'phone' => ['sometimes', 'string'],
            'biography' => ['sometimes', 'string'],
            'social' => ['sometimes'],
            'resume_link' => ['sometimes', 'string'],
            'major' => ['sometimes', 'string'],
        ];
    }
}
