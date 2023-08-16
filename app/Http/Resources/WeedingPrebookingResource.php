<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WeedingPrebookingResource extends JsonResource
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
            'user_id' =>  $this->user_id,
            'property_id' =>  $this->property_id,
            'property_name' =>$this->property->name,
            'location_id' =>$this->property->location->id,
            'location_name' =>$this->property->location->name,
            'check_in' =>  $this->check_in->format('d-m-Y'),
            'check_out' =>  $this->check_out->format('d-m-Y'),
            'bride_name' =>  $this->bride_name,
            'groom_name' =>  $this->groom_name
        ];
    }
}
