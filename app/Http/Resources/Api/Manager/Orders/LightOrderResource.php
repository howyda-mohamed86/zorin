<?php

namespace Tasawk\Http\Resources\Api\Manager\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;
use Tasawk\Http\Resources\Api\Customer\AddressBookResource;
use Tasawk\Http\Resources\Api\Customer\Cart\CartProductResource;
use Tasawk\Http\Resources\Api\Customer\RateResource;

class LightOrderResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request) {
        $cart = $this->as_cart;
        return [
            "id" => $this->id,
            "status" => __("panel.enums." . $this->status->value),
            "status_code" => $this->status,
            "receipt_method" => __("panel.enums." . $this->receipt_method->value),
            "date" => $this->date?->format("Y-m-d h:i a") ?? __("Not yet determined"),
            'customer_name' => $this->customer->name

        ];
    }
}
