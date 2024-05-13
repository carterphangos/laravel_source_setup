<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = auth()->user()->id;

        return [
            'email' => ['required', 'email', Rule::unique('users')->ignore($userId)],
            'current_password' => 'required|current_password',
            'new_password' => 'required|confirmed|min:8',
            'new_password_confirmation' => 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
