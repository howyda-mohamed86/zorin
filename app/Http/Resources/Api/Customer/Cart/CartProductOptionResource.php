<?php

namespace Tasawk\Http\Resources\Api\Customer\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CartProductOptionResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            'id' => $this['id'],
            'name' => $this['name'][app()->getLocale()],
            'price' => $this['price'],
            'value' => $this['value'][app()->getLocale()],
            'value_id' => $this['value_id'],

        ];
    }

}
