<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->user_type === 'admin';
    }


    public function rules(): array
    {
        $staff = $this->route('staff');
        $userId = $staff->user_id ?? null;

        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'username'  => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($userId)
            ],
            // -- Password can be changed but not required on edit:
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'role_type'      => ['nullable', 'string', 'max:100'],
            'rate' => ['nullable', 'numeric', 'min:0'],
            'rate_type' => ['nullable', 'in:per_event,per_day,per_hour'],
            'address'        => ['nullable', 'string', 'max:255'],
            'gender'         => ['nullable', 'in:male,female,other'],
            'remarks'        => ['nullable', 'string'],
            'is_active'      => ['sometimes', 'boolean'],
        ];
    }
}
