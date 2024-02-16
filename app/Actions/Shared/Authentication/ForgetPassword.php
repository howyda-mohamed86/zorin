<?php

namespace Tasawk\Actions\Shared\Authentication;


use Lorisleiva\Actions\Concerns\AsAction;
use Tasawk\Lib\Utils;

class ForgetPassword {
    use AsAction;

    public function handle($user) {
        $code = Utils::randomOtpCode();
        $user->verificationCodes()->create(['phone' => $phone ?? $user->phone, "code" => $code]);


    }

}
