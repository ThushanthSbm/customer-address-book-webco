<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:customers,email',
            'country' => 'required|string|max:255',
            'addresses' => 'required|array',
            'addresses.*.number' => 'required|string|max:255',
            'addresses.*.street' => 'required|string|max:255',
            'addresses.*.city' => 'required|string|max:255',
        ];
    }
}
