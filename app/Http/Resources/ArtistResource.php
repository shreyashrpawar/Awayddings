<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
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
             'name' =>  $this->name,
             'image_url' =>$this->image->url,
             'artistsPersion' => ArtistPersonResource::collection($this->artist_person)->isEmpty()
        ];
    }
}
