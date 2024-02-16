<?php

namespace Tasawk\Http\Resources\Api\Manager;

use Illuminate\Http\Resources\Json\JsonResource;
use Tasawk\Http\Resources\Api\Customer\Products\AllergenResource;
use Tasawk\Http\Resources\Api\Customer\Products\ProductOptionResource;

class ProductResource extends JsonResource {


    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->product->title,
            'status' => $this->status,
        ];
    }
}
