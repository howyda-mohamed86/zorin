<?php

namespace Tasawk\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tasawk\Enum\OrderPaymentStatus;
use Cknow\Money\Casts\MoneyDecimalCast;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Tasawk\Models\Catalog\Category;


class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_number', // Add this line
        'customer_id',
        'service_provider_id',
        'category_id',
        'individual_service_id',
        'category_type',
        'hotel_id',
        'hotel_service_id',
        'date_from',
        'date_to',
        'month',
        'from',
        'to',
        'total',
        'night_price',
        'payment_status',
        'payment_data',
        'payment_type',
        'night_count',
        'status',
        'notes',
        'data',
    ];
    protected $casts = [
        'total' => MoneyDecimalCast::class,
        'date_from' => 'date',
        'date_to' => 'date',
        'to' => 'integer',
        'from' => 'integer',
        'data' => 'json',
        'payment_data' => 'array',
        'status' => 'boolean',
        'payment_status' => OrderPaymentStatus::class,
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }


    public function serviceProvider()
    {
        return $this->belongsTo(User::class, 'service_provider_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
