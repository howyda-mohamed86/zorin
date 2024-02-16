<?php

namespace Tasawk\Models\Content;

use Tasawk\Traits\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property bool $status
 */
class Page extends Model {
    use HasFactory, HasTranslations,Publishable;

    protected $fillable = ['title', 'description', 'status'];
    public $translatable = ['title', 'description'];


}
