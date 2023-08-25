<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventBookingResource extends JsonResource
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
            //'status' =>  $this->pre_booking_summary_status->name,
            'total_amount' =>  $this->total_amount,
            'property_name' =>$this->property->name,
            'property_image' =>$this->property->featured_image,
            'location_id' =>$this->property->location->id,
            'location_name' =>$this->property->location->name,
            'check_in' =>  $this->check_in->format('d-m-Y'),
            'check_out' =>  $this->check_out->format('d-m-Y'),
            // 'bride_name' =>  $this->bride_name,
            // 'groom_name' =>  $this->groom_name,
            'booking_details' => EventBookingDetailsResource::collection($this->booking_details),
            'addson_event_details' =>  AdditionalEventResource::collection($this->bookingAddsonDetails),
             'addson_artist_person' => sizeof($this->bookingAddsonArtistPerson) == 0? null : new AdditionalArtistPersonResource($this->bookingAddsonArtistPerson[0])
        ];
    }
}
