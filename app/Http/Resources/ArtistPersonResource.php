<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArtistPersonResource extends JsonResource
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
            'id' =>  $this->id,
            'price' =>  $this->price,
            'name' =>  $this->name,
            'image_url' =>$this->image->url,
             'url' =>  $this->artist_person_link
        ];
    }
}
