<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdditionalArtistPersonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'artist_id' =>  $this->id,
            'artist_person_name' => $this->addson_artist_person->name??null,
            'artist_person_amount' =>$this->addson_artist_person->price??null,
            'artist_person_image' => $this->addson_artist_person->image->url??null,
        ];
    }
}
