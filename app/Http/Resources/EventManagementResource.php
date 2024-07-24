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
            'additional_facility' => AdditionalFacilityResource::collection($this['additional_facility']),
            'additional_artist' => ArtistResource::collection($this['additional_artist'])->isEmpty(),
            'prefilled_data' => new WeedingPrebookingResource($this['prefilled_data'])
        ];
    } 
}
