<?php

namespace Tasawk\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRate extends Model {
    protected $fillable = ['comment', 'rate'];
    protected $casts = [
        'rate' => 'array'
    ];
}
