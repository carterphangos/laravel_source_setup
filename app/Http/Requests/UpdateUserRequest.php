<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = auth()->user()->id;
        $currentUserRole = auth()->user()->role;

        $rules = [
            'new_password' => 'required|confirmed|min:8',
            'new_password_confirmation' => 'required',
        ];

        if ($currentUserRole !== 'admin') {
            $rules['current_password'] =  'required|current_password';
            $rules['email'] = ['required', 'email', Rule::unique('users')->ignore($userId)];
        } else {
            $rules['email'] = ['required', 'email'];
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
