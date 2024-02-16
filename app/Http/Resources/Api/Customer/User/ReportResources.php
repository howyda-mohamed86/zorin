<?php

namespace Tasawk\Http\Resources\Api\Customer\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Payment;

class ReportResources extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {

        return [
            'id' => $this->id,
            'title' => $this->mapper(),
            "payments" => $this->payment->map(function ($payment) {
                $provider = Payment::provider($payment->provider_id);
                return [
                    "id" => $provider->title(),
                    "logo" => $provider->logo(),
                    "price" => (float)$payment->total
                ];
            })[0],
            'due_date' => $this->created_at->format("Y-m-d H:i a"),
            'total' => $this->as_cart->getTotal(),
        ];
    }

    public function mapper() {
        return match ($this->order_type) {
            'online_reservation' => __("Pay reservation number :NUMBER", ['NUMBER' => $this->id]),
            'purchase_tutorials' => __("Purchase Tutorial :TUTORIAL", ['TUTORIALS' => $this->as_cart->getContent()->map(
                fn($content)=>collect($content->associatedModel->translations)->where('locale',app()->getLocale())->first()['name'])
                ->implode(',')]),
            default => 'ass'
        };
    }
}
