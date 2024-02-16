<?php

namespace Tasawk\Actions\Shared\Authentication;

use Lorisleiva\Actions\Concerns\AsAction;
use Tasawk\Models\User;

class RemoveVerficationCodes {
    use AsAction;

    public function handle(User $user) {
        return $user->verificationCodes()->delete();
    }

}
