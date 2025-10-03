<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->user_type === 'admin';;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255', Rule::unique('services', 'name')->ignore($this->service)],
            'description' => ['nullable', 'string'],
            'price'  => ['required', 'numeric', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }
}
