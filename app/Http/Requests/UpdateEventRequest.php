<?php

namespace App\Http\Requests;

use App\Rules\MaxEventsPerDay;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
        $eventId = $this->route('event')->id ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'event_date' => [
                'required',
                'date',
                'after_or_equal:today',
                new MaxEventsPerDay($eventId, maxEvents: 2)
            ],
            'venue' => ['nullable', 'string', 'max:255'],
            'theme' => ['nullable', 'string', 'max:255'],
            'guests' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'package_id' => ['required', 'exists:packages,id'],
            'inclusions' => ['nullable', 'array'],
            'inclusions.*' => ['exists:inclusions,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'event_date.after_or_equal' => 'The event date must be today or a future date.',
            'package_id.required' => 'Please select a package for your event.',
            'package_id.exists' => 'The selected package is invalid.',
        ];
    }
}
