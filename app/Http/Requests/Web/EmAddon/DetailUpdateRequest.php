<?php

namespace App\Http\Requests\Web\EmAddon;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DetailUpdateRequest extends FormRequest
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
            'facility_details_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'status' => 'nullable|bool'
        ];
    }
}
