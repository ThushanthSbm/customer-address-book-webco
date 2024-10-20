<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'customers' => 'required|array',
            'customers.*' => 'exists:customers,id', // Ensure customers are valid
        ];
    }
}
