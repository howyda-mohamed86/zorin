<?php

namespace Tasawk\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Tasawk\Traits\Publishable;

class CancellationReason extends Model {
    use  Publishable, HasTranslations;

    protected array $translatable = ['name'];
    protected $fillable = ['name', 'status'];
}
