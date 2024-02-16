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

class OrderOfferResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request) {

        return [
            "id" => $this->id,
            "name" =>$this->name,
            "description" => $this->description,
            "price" =>$this->price,
        ];
    }
}
