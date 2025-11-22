<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Responses\ApiResponse;

class UserRegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'     => 'required|string|max:55',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = ApiResponse::error(
            'Validation error',
            $validator->errors(),
            422
        );

        throw new ValidationException($validator, $response);
    }
}
