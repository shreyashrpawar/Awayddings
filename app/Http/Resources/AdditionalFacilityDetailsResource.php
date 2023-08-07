<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdditionalFacilityDetailsResource extends JsonResource
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
              'price' => $this->price,
              'description' => $this->description,
              'image_url' =>$this->image->url,
              //'artistsPersion' => ArtistPersonResource::collection($this->artist_person)
        ];
    }
}
