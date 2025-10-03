<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->user_type === 'admin';
    }


    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255', 'unique:users,email'],
            'username'       => ['nullable', 'string', 'max:50', 'unique:users,username'],
            'password'       => ['nullable', 'string', 'min:8'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'role_type'      => ['nullable', 'string', 'max:100'],
            'rate' => ['nullable', 'numeric', 'min:0'],
            'rate_type' => ['nullable', 'in:per_event,per_day,per_hour'],
            'address'        => ['nullable', 'string', 'max:255'],
            'gender'         => ['nullable', 'in:male,female,other'],
            'remarks'        => ['nullable', 'string', 'max:2000'],
            'is_active'      => ['sometimes', 'boolean'],
        ];
    }
}
