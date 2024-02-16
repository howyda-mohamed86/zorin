<?php

namespace Tasawk\Actions\Shared\Authentication;

use Lorisleiva\Actions\Concerns\AsAction;
use Tasawk\Actions\Shared\Authentication\ForgetPassword;



class SendOTPCodeAction {
    use AsAction;

    public function handle($user) {
        ForgetPassword::run($user);

    }

}
