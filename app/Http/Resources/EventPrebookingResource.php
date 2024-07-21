<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventPrebookingResource extends JsonResource
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
            'guest' =>  $this->pax,
            'status' =>  $this->pre_booking_summary_status->name,
            'total_amount' =>  $this->total_amount,
            'property_name' =>$this->property->name,
            'property_image' =>$this->property->featured_image,
            'location_id' =>$this->property->location->id,
            'location_name' =>$this->property->location->name,
            'check_in' =>  $this->check_in->format('d-m-Y'),
            'check_out' =>  $this->check_out->format('d-m-Y'),
            'bride_name' =>  $this->bride_name,
            'groom_name' =>  $this->groom_name,
            'booking_details' => EventPrebookingDetailsResource::collection($this->event_pre_booking_details),
            'addson_event_details' =>   AdditionalEventResource::collection($this->event_pre_booking_addson_details),
            'addson_artist_person' => sizeof($this->event_pre_booking_addson_artist_person) == 0? null : new AdditionalArtistPersonResource($this->event_pre_booking_addson_artist_person[0])
        ];
    }
}
