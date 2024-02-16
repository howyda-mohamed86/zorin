<?php

namespace Tasawk\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Tasawk\Traits\Publishable;
use Spatie\Translatable\HasTranslations;
use Tasawk\Models\Catalog\Category;


class IndividualService extends Model implements HasMedia
{
    use HasTranslations, Publishable, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'service_provider_id',
        'service_name',
        'service_description',
        'price_night',
        'area',
        'number_of_rooms',
        'number_of_beds',
        'number_of_adults',
        'number_of_children',
        'public_utilities',
        'location',
        'address',
        'notes',
        'status',
        'created_by',
    ];

    protected $appends = [
        'default',
        // 'is_public_utility_text'
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_public_utility' => 'boolean',
        'location' => 'array',
        'address' => 'array',
        'notes' => 'array',
        'service_name' => 'array',
        'service_description' => 'array',
        'public_utilities' => 'array',
    ];

    protected $translatable = [
        'service_name',
        'service_description',
        'address',
        'notes',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function serviceProvider()
    {
        return $this->belongsTo(User::class, 'service_provider_id');
    }

    public function getDefaultAttribute()
    {
        return $this->getMedia('default');
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

    public function getNotesTextAttribute()
    {
        $notes = array_map(function ($note) {
            return $note['note'];
        }, $this->notes);
        return implode(', ', $notes);
    }
}
