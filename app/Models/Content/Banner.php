<?php

namespace Tasawk\Models\Content;

use Tasawk\Traits\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Banner extends Model implements HasMedia {
    use Publishable;
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'status',
    ];


}
