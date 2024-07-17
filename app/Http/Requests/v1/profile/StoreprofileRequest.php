<?php

namespace App\Http\Requests\v1\profile;

use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreprofileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $response = Gate::authorize('create', Profile::class);

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
            'name' => ['string', 'required'],
            'email' => ['string', 'email', 'required'],
            'speciality' => ['string', 'required'],
            'phone' => ['string', 'required'],
            'biography' => ['string', 'required'],
            'social' => ['required'],
            'resume_link' => ['string', 'required'],
            'major' => ['string', 'required', 'unique:profiles'],
        ];
    }
}
