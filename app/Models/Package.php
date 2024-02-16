<?php

namespace Tasawk\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Tasawk\Traits\Publishable;

class Package extends Model
{
    use HasTranslations, Publishable;
    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'percentage',
        'number_of_ads',
        'price',
        'duration',
        'mapper_id',
        'status'
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public function serviceProviderRequests()
    {
        return $this->hasMany(ServiceProviderRequest::class);
    }

}
