<?php

namespace Tasawk\Http\Resources\Api\Hotel;

use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'location' => ("https://maps.google.com/?q=" . $this->location['lat'] . "," . $this->location['lng']),
            'notes' => $this->notes,
            'image' => $this->getFirstMediaUrl(),
            'status' =>  $this->status == 1 ?  __('forms.fields.active') : __('forms.fields.inactive'),
            'services' => HotelServicesResource::collection($this->whenLoaded('hotelServices')),
        ];

    }
}
