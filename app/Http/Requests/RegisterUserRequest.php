<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'birthday' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|integer|in:1,2', // Only allow Customer (1) or Vendor (2)
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'role_id.required' => 'Please select a role (Customer or Vendor)',
            'role_id.in' => 'Invalid role selected. Please choose either Customer or Vendor. Admin roles are restricted.',
        ];
    }
}
