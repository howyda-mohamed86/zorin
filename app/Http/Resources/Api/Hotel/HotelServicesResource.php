<?php

namespace Tasawk\Http\Resources\Api\Hotel;

use Illuminate\Http\Resources\Json\JsonResource;

class HotelServicesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'service_name' => $this->service_name,
            'description_service' => $this->description_service,
            'price_night' => $this->price_night,
            'area' => $this->area,
            'number_of_rooms' => $this->number_of_rooms,
            'number_of_beds' => $this->number_of_beds,
            'number_of_children' => $this->number_of_children,
            'number_of_adults' => $this->number_of_adults,
            'public_utilities' => $this->public_utilities_data,
            'location' => $this->location,
            'status' =>  $this->status == 1 ?  __('forms.fields.active') : __('forms.fields.inactive'),
            'image' => $this->default_image,
        ];

    }
}
