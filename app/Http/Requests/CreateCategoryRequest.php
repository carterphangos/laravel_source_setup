<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CreateCategoryRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        if ($this->name) {
            $category = Category::withTrashed()->where('name', $this->name)->first();
            if ($category) {
                if ($category->trashed()) {
                    $category->forceDelete();
                }
            }
        }
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('categories', 'name')->whereNull('deleted_at'),
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
