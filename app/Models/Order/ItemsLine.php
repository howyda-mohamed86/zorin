<?php

namespace Tasawk\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsLine extends Model {
    use HasFactory;
    protected $table = 'orders_items_lines';
    protected $casts = [
        'attributes' => 'array',
        'conditions' => 'array',
        'model' => 'array',
    ];
    protected $fillable=['name','quantity','price','attributes','conditions','model'];
    public $timestamps=false;
public function getCreatedAtColumn() {
}

}
