<?php

namespace App\Http\Requests;

use App\Http\Responses\ApiResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ComplaintStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|string|in:new,in_progress,resolved,rejected'
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
