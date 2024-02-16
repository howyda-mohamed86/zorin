<?php

namespace Tasawk\Http\Resources\Api\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use Tasawk\Http\Resources\Api\Shared\ZoneResource;

class AddressBookResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "phone" => $this->phone,
            "state" => $this->state,
            "street" => $this->street,
            "building_number" => $this->building_number,
            'floor'=>$this->floor,
            "location" => $this->map_location,
            "zone" => ZoneResource::make($this->zone),
            'notes'=>$this->notes,
            'primary'=>$this->primary,
        ];
    }
}
