<?php

namespace Tasawk\Actions\Customer;

use Lorisleiva\Actions\Concerns\AsAction;
use Notification;
use Tasawk\Models\User;


class FireWelcomeMessageAction {
    use AsAction;

    public function handle(User $user) {
        $user->update(['remember_token' => now()]);
//        Notification::send(auth()->user(), new CustomerWelcomeMessageNotification());
    }

}
