<?php

namespace Tasawk\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Customer extends User {
    protected $table = "users";
    protected string $guard_name = 'web';
    const  ROLE = 'customer';

    protected $appends = ['full_name'];

    public function getMorphClass(): string {
        return User::class;
    }

    protected static function booted() {
        parent::booted();
        static::creating(fn($model) => $model->assignRole('customer'));
        static::addGlobalScope("customer", function ($builder) {
            $builder->whereHas("roles", fn($q) => $q->where('name', 'customer'));
        });
    }

    public function orders() {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function getFullNameAttribute() {
        return $this->name . " " . $this->last_name;
    }
}
