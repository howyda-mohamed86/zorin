<?php

namespace Tasawk\Models;

use Cknow\Money\Casts\MoneyDecimalCast;
use Darryldecode\Cart\CartCondition;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Tasawk\Enum\OrderPaymentStatus;
use Tasawk\Enum\OrderStatus;
use Tasawk\Lib\ArrayStorage;
use Tasawk\Lib\Cart as CoreCart;
use Tasawk\Models\Order\Condition;
use Tasawk\Models\Order\ItemsLine;


class Order extends Model implements HasMedia {
    use InteractsWithMedia;

    protected $fillable = [
        'customer_id',
        'date',
        'payment_status',
        'payment_data',
        'status',
        'total',
        'notes',
        'data',

    ];
    protected $casts = [
        'total' => MoneyDecimalCast::class,
        'date' => 'datetime',
        'data' => 'json',
        'payment_data' => 'array',
        'status' => OrderStatus::class,
        'payment_status' => OrderPaymentStatus::class,
    ];




    public function getOrderNumberAttribute(): string {
        return sprintf("%'.06d", $this->id);
    }


    function itemsLine(): HasMany {
        return $this->hasMany(ItemsLine::class);
    }

    function conditions(): HasMany {
        return $this->hasMany(Condition::class);
    }

    public function getAsCartAttribute() {
        $eventsClass = config('shopping_cart.events');
        $events = $eventsClass ? new $eventsClass() : app('events');
        $session_key = md5($this->cart_id . \Str::random());
        $instanceName = $session_key . 'back_end_order_cart';
        $cart = new CoreCart(
            new ArrayStorage,
            $events,
            $instanceName,
            $session_key,
            config('shopping_cart')
        );

        $this->itemsLine->transform(function (ItemsLine $item) {

            $conditions = collect($item->conditions)->map(fn($cond) => new CartCondition($cond))->toArray();
            $item['quantity'] = $item->quantity > 0 ? $item->quantity : 1;
            $item['associatedModel'] = (object)$item->model;
            $item['new_conditions'] = $conditions;
            return $item;
        })->each(function ($item) use ($cart) {
            $d = $item->toArray();
            $d['conditions'] = $d['new_conditions'];
            return $cart->add($d);
        });
        $this->conditions->each(fn($condition) => $cart->condition(new CartCondition($condition->toArray())));

        return $cart;
    }


    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function rate() {
        return $this->hasOne(OrderRate::class);
    }

    public function rated() {
        return $this->rate()->exists();
    }


    public function cancellation(): HasOne {
        return $this->hasOne(OrderCancellation::class, "order_id");
    }
    public function canRate(): bool {
        return !$this->rate()->exists() && $this->status == OrderStatus::DELIVERED;
    }


}
