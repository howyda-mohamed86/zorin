<?php

namespace Tasawk\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model {
    use HasFactory;
    protected $table = 'orders_conditions';
    protected $casts = [
        'attributes' => 'array',
        'conditions' => 'array',
        'model' => 'array',
    ];
}
