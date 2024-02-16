<?php

namespace Tasawk\Actions\Shared\Authentication;

use Lorisleiva\Actions\Concerns\AsAction;
use Tasawk\Actions\Shared\Authentication\SendVerificationCode;

class ChangeUserPhone {
    use AsAction;

    public function handle($user,$phone) {
        SendVerificationCode::run($user, $phone);
    }

}
