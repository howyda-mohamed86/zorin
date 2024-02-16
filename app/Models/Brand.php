<?php

namespace Tasawk\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Tasawk\Models\Catalog\Product;
use Tasawk\Traits\Publishable;

class Brand extends Model implements HasMedia {
    use HasTranslations, Publishable, InteractsWithMedia;

    protected $fillable = ['name', 'status'];
    public $translatable = ['name'];

    public function products(): HasMany {
        return $this->hasMany(Product::class);
    }
}
