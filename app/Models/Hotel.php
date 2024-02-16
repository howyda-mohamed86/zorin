<?php

namespace Tasawk\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Tasawk\Traits\Publishable;
use Spatie\Translatable\HasTranslations;


class Hotel extends Model implements HasMedia
{
    use HasTranslations, Publishable, InteractsWithMedia;
    protected $table = 'hotels';
    protected $appends = [
        'status_text',
        'default',
        'notes_text'
    ];
    protected $fillable = [
        'name',
        'service_provider_id',
        'address',
        'location',
        'notes',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $translatable = [
        'name',
        'address',
        'notes',
    ];

    protected $casts = [
        'name' => 'array',
        'address' => 'array',
        'location' => 'array', // 'latitude', 'longitude
        'status' => 'boolean',
        'notes' => 'array',
    ];

    public function service_provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'service_provider_id', 'id');
    }

    public function hotelServices()
    {
        return $this->hasMany(HotelService::class);
    }

    public function getDefaultAttribute()
    {
        return $this->getFirstMediaUrl('default');
    }
    public function getNotesTextAttribute()
    {
        $notes = array_map(function ($note) {
            return $note['note'];
        }, $this->notes);
        return implode(', ', $notes);
    }
    public function getStatusTextAttribute()
    {
        return $this->status == 1 ? __('forms.fields.active') : __('forms.fields.inactive');
    }
}
