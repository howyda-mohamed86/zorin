<?php

namespace Tasawk\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tasawk\Models\User;

class Contact extends Model {


    protected $fillable = [

        "message",
        "user_id",
        "name",
        "email",
        "phone",
        "seen",

    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class,);
    }


}
