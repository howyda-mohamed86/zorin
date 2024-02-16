<?php

namespace Tasawk\Actions\Shared\Authentication;

use Lorisleiva\Actions\Concerns\AsAction;
use Tasawk\Models\User;

class UpdateUserPassword {
    use AsAction;

    public function handle(User $user, $password) {
        $user->update(['password' => $password]);
        UpdateUserToken::run($user);
        RemoveVerficationCodes::run($user);
    }

}
