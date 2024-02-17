<?php

namespace Tasawk\Http\Resources\Api\Hotel\Reservation;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'service_name' => $this->service_name,
            'hotel_id' => $this->hotel_id,
            'price_night' => $this->price_night,
            'area' => $this->area,
            'number_of_rooms' => $this->number_of_rooms,
            'number_of_beds' => $this->number_of_beds,
            'number_of_children' => $this->number_of_children,
            'number_of_adults' => $this->number_of_adults,
        ];

    }
}
