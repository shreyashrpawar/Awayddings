<?php

namespace App\Http\Requests\Api\Booking;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePreBookingRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'property_id' => 'required|exists:properties,id',
            'check_in' => [
                'required',
                'date',
                'after_or_equal:' . now()->format('Y-m-d')
            ],
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|int',
            'total_budget' => 'required|numeric',
            'user_budget' => 'required|numeric',
            'user_remarks' => 'nullable|string',
            'status' => 'nullable|bool',
            'bride_name' => 'nullable|string',
            'groom_name' => 'nullable|string',
            'details' => 'required|array'
        ];
    }
}
