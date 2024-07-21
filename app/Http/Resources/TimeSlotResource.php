<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimeSlotResource extends JsonResource
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
             //'name' =>  $this->name,
             'from_time' => $this->from_time,
             'to_time' => $this->to_time,
              //'artistsPersion' => ArtistPersonResource::collection($this->artist_person)
        ];
    }
}
