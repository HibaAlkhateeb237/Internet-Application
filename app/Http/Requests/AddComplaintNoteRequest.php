<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddComplaintNoteRequest extends FormRequest
{
    public function rules()
    {
        return [
            'note' => 'required|string|max:500'
        ];
    }
}
