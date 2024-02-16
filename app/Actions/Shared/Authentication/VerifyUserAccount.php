<?php

namespace Tasawk\Actions\Shared\Authentication;

use Lorisleiva\Actions\Concerns\AsAction;
use Notification;
use Tasawk\Models\User;
use Tasawk\Notifications\Customer\CustomerRegisteredNotification;


class VerifyUserAccount {
    use AsAction;

    public function handle(User $user) {
        $user->update(['phone_verified_at' => now()]);
        UpdateUserToken::run($user);
        RemoveVerficationCodes::run($user);
        Notification::send($user, new CustomerRegisteredNotification());


    }

}
