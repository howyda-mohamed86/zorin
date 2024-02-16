<?php

namespace Tasawk\Http\Resources\Api\Manager;

use Illuminate\Http\Resources\Json\JsonResource;
use Tasawk\Http\Resources\Api\Customer\Products\AllergenResource;

class ProductOptionValueResource extends JsonResource {


    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->value->value->value,
            'price' => $this->value->price->format(),
            'status' => $this->status,
        ];
    }
}
