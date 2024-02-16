<?php

namespace Tasawk\Models;

use Tasawk\Traits\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Zone extends Model {
    use HasFactory, HasTranslations, Publishable;

    public array $translatable = ['name'];


    protected $fillable = [
        'name',
        'status'

    ];


}
