<?php

namespace Tasawk\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Tasawk\Traits\Publishable;

class ServiceProviderRequest extends Model implements HasMedia
{
    use Publishable;
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'iban',
        'national_id',
        'national_type',
        'password',
        'status',
        'package_id',
    ];

    protected $appends = ['national_type_text', 'status_text'];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function getNationalTypeTextAttribute()
    {
        return __('forms.fields.' . $this->national_type);
    }
    public function getStatusTextAttribute()
    {
        return __('forms.fields.' . $this->status);
    }
    public function setPasswordAttribute($value): void
    {
        if ($value && !app()->runningInConsole()) {
            $this->attributes["password"] = bcrypt($value);
        }
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
