<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseMeiliRequest extends FormRequest
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

        $filters = is_array($this->filters) ? $this->filters : json_decode($this->filters, true);
        if (!empty($filters)) {
            $this->merge([
                'filters' => $filters,
            ]);
        }
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
            "sort" => 'sometimes|string',
            "limit" => 'sometimes|integer',
            "page" => 'sometimes|integer',
            'search' => 'required|string|max:200',
        ];
    }

    protected function passedValidation()
    {
        $filters = $this->filters ?? [];
        $limit = $this->limit ?? 50;
        $page = $this->page ?? 1;
        $sort = $this->sort ?? 'modified_desc';
        $search = $this->search ?? '';
        //init
        $this->replace(
            compact('filters','limit','page','sort','search')
        );
    }

    protected function failedValidation(Validator $validator)
    {
        $errors=$validator->errors()->all();
        throw new HttpResponseException(response()->json([
            'message' => $errors[0],
            'errors' => $validator->errors(),
        ], 402));
    }


}
