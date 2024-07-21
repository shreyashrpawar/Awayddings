<?php

namespace App\Http\Requests\Web\Artist;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateArtistPersonRequest extends FormRequest
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
            'artist_person_name' => 'required|string',
            'artist_person_price' => 'required|numeric',
            'artist_person_link' => 'required|url',
            'artist_id' => 'required|exists:em_artists,id',
            'artist_person_image' => 'nullable|image|mimes:jpeg,jpg,png',
            'status' => 'nullable|bool'
        ];
    }
}
