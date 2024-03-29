<?php

namespace Tasawk\Models\Catalog;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Tasawk\Models\Brand;
use Tasawk\Models\Design\Pattern;
use Tasawk\Traits\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Tasawk\Models\IndividualService;

class Category extends Model  {
    use HasTranslations, Publishable;

    protected $fillable = ['name', 'status'];
    public $translatable = ['name'];
    use HasFactory;


    public function individualServices(): HasMany
    {
        return $this->hasMany(IndividualService::class);
    }







}
