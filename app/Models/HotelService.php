<?php

namespace Tasawk\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Tasawk\Traits\Publishable;
use Spatie\Translatable\HasTranslations;

class HotelService extends Model implements HasMedia
{
    use HasTranslations, Publishable, InteractsWithMedia;
    protected $fillable = [
        'service_name',
        'hotel_id',
        'description_service',
        'price_night',
        'area',
        'number_of_rooms',
        'number_of_beds',
        'number_of_children',
        'number_of_adults',
        'public_utilities',
        'location',
        'status',
        'created_by',
    ];

    protected $appends = [
        'default' ,  'public_utilities_text', 'default_image' ,'public_utilities_data'
    ];

    protected $translatable = [
        'service_name',
        'description_service',
        'location',
    ];

    protected $casts = [
        'service_name' => 'array',
        'description_service' => 'array',
        'location' => 'array',
        'status' => 'boolean',
        'public_utilities'  => 'array',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function publicUtilities()
    {
        return $this->belongsToMany(PublicUtility::class);
    }

    public function getPublicUtilitiesDataAttribute()
    {
        $publicUtilities = PublicUtility::whereIn('id', $this->public_utilities)
        ->select('id', 'name->' . app()->getLocale() . ' as public_utility_name')
        ->get();
        return $publicUtilities;
    }

    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    public function getDefaultAttribute()
    {
        return $this->getMedia('default');
    }
    public function getDefaultImageAttribute()
    {
        $images = [];
        foreach ($this->getMedia('default') as $image) {
            $images[] = [
                'image' => $image->getUrl()
            ];
        }
        return $images;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default');
    }


    public function getStatusTextAttribute()
    {
        return $this->status == 1 ? __('forms.fields.active') : __('forms.fields.inactive');
    }

    public function getPublicUtilitiesTextAttribute()
    {
        $publicUtilities = PublicUtility::whereIn('id', $this->public_utilities)->pluck('name')->toArray();
        return implode(', ', $publicUtilities);
    }
}
