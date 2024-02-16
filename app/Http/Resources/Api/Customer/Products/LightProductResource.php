<?php

namespace Tasawk\Http\Resources\Api\Customer\Products;

use Illuminate\Http\Resources\Json\JsonResource;
use Tasawk\Lib\Utils;

class LightProductResource extends JsonResource {


    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->getFirstMediaUrl(),
            'price' => $this->price->format(),


        ];
    }
}
