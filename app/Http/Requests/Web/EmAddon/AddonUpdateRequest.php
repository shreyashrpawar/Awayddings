<?php

namespace App\Http\Requests\Web\EmAddon;

use Illuminate\Foundation\Http\FormRequest;

class AddonUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'facility_name' => 'required',
            'status' => 'nullable|bool'
        ];
    }
}
