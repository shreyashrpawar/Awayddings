<?php

namespace App\Http\Requests\Web\Artist;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'artist_image' => 'nullable|image|mimes:jpeg,jpg,svg,png',
            'artist_name' => [
                'required',
                'string',
                Rule::unique('em_artists','name')->ignore($this->artist)
            ],
            'description' => 'nullable|string',
            'status' => 'nullable|bool'
        ];
    }
}
