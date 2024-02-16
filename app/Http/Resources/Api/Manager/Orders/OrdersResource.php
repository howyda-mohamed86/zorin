<?php

namespace Tasawk\Http\Resources\Api\Manager\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;
use Tasawk\Http\Resources\Api\Customer\AddressBookResource;
use Tasawk\Http\Resources\Api\Customer\Cart\CartProductResource;
use Tasawk\Http\Resources\Api\Customer\RateResource;

class OrdersResource extends JsonResource {

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
            "created_date" => $this->created_at->format("Y-m-d h:i a"),
            'payment' => [
                'url' => $this->payment_data['invoiceURL'] ?? null,
                'status' => __("panel.enums." . $this->payment_status->value),
                'status_code' => Str::headline($this->payment_status->value),
                'gateway' => $this->payment_data['gateway'] ?? null,
                'method' => $this->payment_data['method'] ?? null,
            ],
            'customer_name'=>$this->customer->name,
            'address' => $this->address->name,
            'products' => CartProductResource::collection($this->as_cart->getContent()),
//            "can_rate" => $this->canRate(),
            $this->mergeWhen($this->cancellation()->exists(), [
                'cancellation_reason' => $this->cancellation?->reason?->name
            ]),
            $this->mergeWhen($this?->rated(), [
                'rate' => RateResource::make($this?->rate)
            ]),
            'notes' => $this->notes,
            "totals" => $cart->formattedTotals(),

        ];
    }
}
