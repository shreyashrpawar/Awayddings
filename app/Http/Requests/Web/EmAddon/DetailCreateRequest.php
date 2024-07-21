<?php

namespace App\Http\Requests\Web\EmAddon;

use Illuminate\Foundation\Http\FormRequest;

class DetailCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'facility_details_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'facility_id' => 'required|exists:em_addon_facility,id',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'status' => 'nullable|bool'
        ];
    }
}
