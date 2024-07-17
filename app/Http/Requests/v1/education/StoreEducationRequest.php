<?php

namespace App\Http\Requests\v1\education;

use App\Models\Education;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreEducationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $response = Gate::authorize('create', Education::class);

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
            'source' => ['string', 'required'],
            'country' => ['string', 'required'],
            'major' => ['string', 'required', 'unique:education'],
            'date' => ['date_format:Y-m-d'],
            'courses' => ['required']
        ];
    }
}
