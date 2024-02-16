<?php

namespace Tasawk\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Tasawk\Traits\Publishable;


class ServiceProvider extends User
{
    protected $table = 'users';

    protected string $guard_name = 'web';

    protected $appends = [
        'national_type_text',
        'commercial_register',
        'default',
    ];
    const  ROLE = 'Service Provider';
    public function getMorphClass(): string
    {
        return User::class;
    }

    protected static function booted()
    {
        parent::booted();
        static::creating(fn ($model) => $model->assignRole('Service Provider'));
        static::addGlobalScope("Service Provider", function ($builder) {
            $builder->whereHas("roles", fn ($q) => $q->where('name', 'Service Provider'));
        });
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // hotel
    public function hotels()
    {
        return $this->hasMany(Hotel::class, 'service_provider_id', 'id');
    }

    public function getNationalTypeTextAttribute()
    {
        return __('forms.fields.' . $this->national_type);
    }

    public function getCommercialRegisterAttribute()
    {
        return $this->getFirstMediaUrl('commercial_register');
    }
    public function getDefaultAttribute()
    {
        return $this->getFirstMediaUrl('default');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('commercial_register');
        $this->addMediaCollection('default');
    }


    // public function getStatusTextAttribute()
    // {
    //     return __('forms.fields.' . $this->status);
    // }


}
