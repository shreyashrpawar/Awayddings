<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdditionalEventResource extends JsonResource
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
             'addition_facility_name' =>  $this->addson_facility->name,
             'addition_facility_image_url' =>$this->addson_facility_details->image->url??null,
             'facility_amount' =>$this->addson_facility_details->price??null,
        ];
    }
}
