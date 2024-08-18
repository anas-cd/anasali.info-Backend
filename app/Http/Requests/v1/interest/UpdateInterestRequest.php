<?php

namespace App\Http\Requests\v1\interest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UpdateInterestRequest extends FormRequest
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
            $interest = Auth::user()->interests()->where("id", $this->id)->firstOrFail();

        } catch (\Throwable $th) {
            return false;
        }

        $response = Gate::authorize('update', $interest);

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
            'name' => ["sometimes", "string"],
            'subText' => ["sometimes", "string"],
            'description' => ["sometimes", "string"],
            'link' => ["sometimes", "url"],
            'image' => ["sometimes", "image", "mimes:jpeg,png,jpg,gif|max:2048"]
        ];
    }
}
