<?php

namespace Tasawk\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Tasawk\Traits\Publishable;

class PublicUtility extends Model
{
    use HasTranslations, Publishable;
    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'name' => 'array',
        'status' => 'boolean',
    ];




}
