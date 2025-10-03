<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignEventStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->user_type === 'admin';
    }
    public function rules(): array
    {
        return [
            // Array of staff ids to assign
            'staff_ids'   => ['required', 'array', 'min:1'],
            'staff_ids.*' => ['integer', Rule::exists('staffs', 'id')],

            // (optional) per-staff roles: { staff_id => 'role' }
            'roles'       => ['sometimes', 'array'],
            'roles.*'     => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'staff_ids.required' => 'Please select at least one staff member.',
        ];
    }
}
