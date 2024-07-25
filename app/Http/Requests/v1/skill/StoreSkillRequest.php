<?php

namespace App\Http\Requests\v1\skill;

use App\Models\Skill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreSkillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $response = Gate::authorize('create', Skill::class);

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
            "label" => ["required", "string"],
            "major" => ["required", "string"],
            "description" => ["nullable", "string"],
            "icon" => ["nullable", "string"],
            "type" => ["required", "string", Rule::in(['primary', 'secondary', 'soft'])],
        ];
    }
}
