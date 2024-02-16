<?php

namespace Tasawk\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Manager extends User {
    protected $table = "users";
    protected string $guard_name = 'web';
    const  ROLE = 'manager';

    public function getMorphClass(): string {
        return User::class;
    }

    protected static function booted() {
        parent::booted();
        static::creating(fn($model) => $model->assignRole('manager'));
        static::addGlobalScope("manager", function ($builder) {
            $builder->whereHas("roles", fn($q) => $q->where('name', 'manager'));
        });
    }


}
