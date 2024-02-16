<?php

namespace Tasawk\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Tasawk\Traits\Publishable;

class Post extends Model implements HasMedia {
    use  HasTranslations, Publishable, InteractsWithMedia;

    protected $fillable = ['title', 'description', 'status', 'publish_date'];
    public $translatable = ['title', 'description'];
    protected $casts = ['publish_date' => 'datetime'];
}
