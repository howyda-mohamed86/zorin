<?php

namespace Tasawk\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCancellation extends Model {

    protected $fillable = ['order_id', 'cancellation_reason_id'];

    protected $table = 'orders_cancellation';

    public function reason() {
        return $this->belongsTo(CancellationReason::class, 'cancellation_reason_id');
    }

    public function getReason() {

        if ($this->cancellation_reason_id == 0) {
            return __("panel.messages.customer_not_responded");
        }
        if ($this->cancellation_reason_id == -1) {
            return __("panel.messages.manager_not_responded");
        }
        return $this->reason->name;
    }
}
