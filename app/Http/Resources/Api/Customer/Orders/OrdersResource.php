<?php

namespace Tasawk\Http\Resources\Api\Customer\Orders;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;
use Tasawk\Enum\OrderStatus;
use Tasawk\Enum\OrderTypes;
use Tasawk\Http\Resources\Api\Customer\AddressBookResource;
use Tasawk\Http\Resources\Api\Customer\Cart\CartGroupProductsResource;
use Tasawk\Http\Resources\Api\Customer\Cart\CartProductResource;
use Tasawk\Http\Resources\Api\Customer\RateResource;
use Tasawk\Settings\GeneralSettings;

class OrdersResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            "id" => $this->id,
            "status" => __("panel.enums." . $this->status->value),
            "status_code" => $this->status->value,
            "order_type" => __("panel.enums." . $this->order_type->value),
            "order_type_code" => $this->order_type,
            "date" => $this->date?->format("Y-m-d h:i a") ?? __("Not yet determined"),
            "created_date" => $this->created_at->format("Y-m-d h:i a"),

            $this->mergeWhen($this->order_type == OrderTypes::DESIGN, [
                'pattern' => $this->design->pattern->title,
                'design_url' => $this->design->getFirstMediaUrl(),
                'dimensions' => $this->data['dimensions'],
            ])
            , $this->mergeWhen(in_array($this->order_type, [OrderTypes::VISIT, OrderTypes::GOVERNMENT_ACTIONS]), [
                'place_name' => $this->data['place_name'] ?? '',
                'location' => $this->data['location'] ?? [],
            ]),
            $this->mergeWhen($this->order_type == OrderTypes::VISIT, [
                'length' => $this->data['length'] ?? '',
                'width' => $this->data['width'] ?? '',
            ]),
            'payment' => [
                'url' => $this->payment_data['invoiceURL'] ?? null,
                'status' => __("panel.enums." . $this->payment_status->value),
                'status_code' => Str::headline($this->payment_status->value),
                'gateway' => $this->payment_data['gateway'] ?? null,
                'method' => $this->payment_data['method'] ?? null,
                'paid_at' => isset($this->payment_data['paid_at']) ? Carbon::parse($this->payment_data['paid_at'])->format('Y-m-d h:i a') : null,
            ],
            'offers' => OrderOfferResource::collection($this?->offers ?? []),

            $this->mergeWhen($this->as_cart->getContent()->values(), [
                'groups' => CartGroupProductsResource::collection($this->as_cart->getContent()->groupBy('attributes.group')->values()),
            ]),

            $this->mergeWhen($this->status == OrderStatus::DELIVERED, [
                'invoice_url' => route('orders.invoice', $this->id),
            ]),
            $this->mergeWhen($this->cancellation()->exists(), [
                'cancellation_reason' => $this->cancellation?->reason?->name
            ]),
            $this->mergeWhen($this?->rated(), [
                'rate' => RateResource::make($this?->rate)
            ]),
            $this->mergeWhen($this->getMedia('attachments')->count(), [
                'attachments' => $this->getMedia('attachments')->map(fn($attachment) => $attachment->getUrl()),
            ]),
            $this->mergeWhen($this->getMedia('images')->count(), [
                'images' => $this->getMedia('images')->map(fn($images) => $images->getUrl()),
            ]),
            "can_rate" => $this->canRate(),
            'notes' => $this->notes,
            'totals' => $this->as_cart->formattedTotals()
        ];
    }
}
