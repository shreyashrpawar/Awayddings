<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\EventResource;

class EventManagementResource extends JsonResource
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
            'event' => EventResource::collection($this['event']),
            'lightDecor' => LightDecorResource::collection($this['light_decor']),
            'timeSlot' => TimeSlotResource::collection($this['time_slots'])
        ];
    }
}
