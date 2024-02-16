<?php

namespace Tasawk\Actions\Customer\Orders;

use Lorisleiva\Actions\Concerns\AsAction;
use Tasawk\Models\Order;


class CreateOrderAction {
    use AsAction;

    protected $data = [];

    public function handle( $total, $formData, $notes = null, $images = [], $attachments = []) {
        $order =  Order::create([
            "status" => 'pending',
            'customer_id' => auth()->id(),
            'payment_status' => 'pending',
            'total' => $total,
            'date' => now(),
            'notes' => $notes,
            'data' => $formData
        ]);

        foreach ($images as $image){
            $order->addMedia($image)->toMediaCollection('images');
        }
        foreach ($attachments as $attachment){
            $order->addMedia($attachment)->toMediaCollection('attachments');
        }
        return $order;
    }

}
