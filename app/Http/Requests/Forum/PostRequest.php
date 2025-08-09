<?php

namespace App\Http\Requests\Forum;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'search' => to_standard_letter(trim($this->search)),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'filters' => 'sometimes|array',
            'filters.course' => 'sometimes|string',
            'filters.discussion' => 'sometimes|string',
            "sort" => 'sometimes|string',
            "limit" => 'sometimes|integer',
            'search' => 'required|string|max:200',
        ];
    }


}
