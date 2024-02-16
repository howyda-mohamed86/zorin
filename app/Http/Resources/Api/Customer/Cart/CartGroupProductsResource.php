<?php

namespace Tasawk\Http\Resources\Api\Customer\Cart;

use Arr;
use Cknow\Money\Money;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CartGroupProductsResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            'group_id' => $this->first()->attributes->group,
            'name' => __('panel.enums.'.$this->first()->attributes->group),
            'category_key' => $this->first()->attributes->category_key,
            'type' => $this->first()->attributes->group_type,

            'products' => CartProductResource::collection($this)
        ];
    }

}
