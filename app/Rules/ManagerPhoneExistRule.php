<?php

namespace Tasawk\Rules;

use Tasawk\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Lang;

class ManagerPhoneExistRule implements Rule {
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value) {
//TODO: Replace it with Manager Class instead
        return User::whereHas('roles', fn($q) => $q->where('name', 'manager'))->where('phone', $value)->exists();

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return Lang::get('validation.exists');
    }
}
