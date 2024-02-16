<?php

namespace Tasawk\Http\Resources\Api\Customer\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CartProductConditionResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request) {
        return [

            'name' => $this->getName(),
            'price' => $this->getValue(),

        ];
    }

}
