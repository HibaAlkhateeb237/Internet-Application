<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComplaintStatusRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => 'required|in:new,in_progress,resolved,rejected',
            'note' => 'nullable|string',
        ];
    }
}
