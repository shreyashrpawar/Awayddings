<?php

namespace App\Http\Requests\Web\Artist;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'artist_image' => 'required||image|mimes:jpeg,jpg,svg,png',
            'artist_name' => 'required|string|unique:em_artists,name',
            'description' => 'nullable|string',
            'status' => 'nullable|bool'
        ];
    }
}
