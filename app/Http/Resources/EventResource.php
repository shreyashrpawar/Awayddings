<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'artist_visible' => $this->is_artist_visible,
            'decor_visible' => $this->is_decor_visible,
            'description' => $this->description,
            'artists' => ArtistResource::collection($this->artists),
            'decor' => DecorResource::collection($this->decorations)
        ];
    }
}
