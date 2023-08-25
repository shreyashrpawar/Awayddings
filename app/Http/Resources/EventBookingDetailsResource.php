<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventBookingDetailsResource extends JsonResource
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
            'em_summaries_id' => $this->em_booking_summaries_id,
            'event_start_time' => $this->start_time,
            'event_end_time' => $this->end_time,
            'event_id' =>$this->em_event_id,
            'event_name' => $this->events->name,
            'artist_person_id' =>$this->em_artist_person_id,
            'artist_person_name' => $this->artistPerson->name??null,
            'artist_person_amount' =>$this->artistPerson->price??null,
            'artist_person_image' => $this->artistPerson->image->url??null,
            'decor_id' =>$this->em_decor_id,
            'decor_image' => $this->decoration->image->url??null,
            'decor_amount' =>$this->decoration->price??null,
            'day_total' =>$this->total_amount,
            'date' => $this->date->format('d/m/Y') 
        ];
    }
}
