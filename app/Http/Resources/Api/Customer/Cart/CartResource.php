<?php

namespace Tasawk\Http\Resources\Api\Customer\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Tasawk\Http\Resources\Api\Customer\Products\LightProductResource;
use Tasawk\Models\Catalog\Product;


class CartResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */

    public function toArray($request) {

        return [
            "groups" => CartGroupProductsResource::collection($this->getContent()->groupBy('attributes.group')->values()),
            'totals' => $this->formattedTotals()
        ];
    }

}
