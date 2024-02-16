<?php

namespace Tasawk\Http\Resources\Api\Customer\Orders;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;
use Tasawk\Enum\OrderStatus;
use Tasawk\Http\Resources\Api\Customer\AddressBookResource;
use Tasawk\Http\Resources\Api\Customer\Cart\CartProductResource;
use Tasawk\Http\Resources\Api\Customer\RateResource;
use Tasawk\Settings\GeneralSettings;

class LightOrdersResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request) {
        $cart = $this->as_cart;
        $settings = new GeneralSettings();
        return [
            "id" => $this->id,
            "status" => __("panel.enums." . $this->status->value),
            "status_code" => $this->status->value,
            "order_type" =>__("panel.enums." . $this->order_type->value),
            "order_type_code" => $this->order_type->value,
            "date" => $this->date?->format("Y-m-d h:i a") ?? __("Not yet determined"),
            "created_date" => $this->created_at->format("Y-m-d h:i a"),

        ];
    }
}
