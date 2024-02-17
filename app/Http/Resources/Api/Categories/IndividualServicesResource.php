<?php

namespace Tasawk\Http\Resources\Api\Categories;

use Illuminate\Http\Resources\Json\JsonResource;

class IndividualServicesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'service_name' => $this->service_name,
            'service_description' => $this->service_description,
            'price_night' => $this->price_night,
            'area' => $this->area,
            'number_of_rooms' => $this->number_of_rooms,
            'number_of_beds' => $this->number_of_beds,
            'number_of_adults' => $this->number_of_adults,
            'number_of_children' => $this->number_of_children,
            'public_utilities' => $this->public_utilities_data,
            'location' => ("https://maps.google.com/?q=" . $this->location['lat'] . "," . $this->location['lng']),
            'address' => $this->address,
            'notes' => $this->notes,
            'status' =>  $this->status == 1 ?  __('forms.fields.active') : __('forms.fields.inactive'),
        ];

    }
}
