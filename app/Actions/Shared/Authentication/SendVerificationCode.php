<?php

namespace Tasawk\Actions\Shared\Authentication;

use Lorisleiva\Actions\Concerns\AsAction;
use Tasawk\Lib\NotificationMessageParser;
use Tasawk\Lib\Utils;
use Tasawk\Notifications\OTPCodeSentNotification;

class SendVerificationCode {
    use AsAction;

    public function handle($user, $phone = null) {

        $code = Utils::randomOtpCode();

        $user->verificationCodes()->create(['phone' => $phone ?? $user->phone, "code" => $code]);

        $user?->notify(new OTPCodeSentNotification($code));

        dispatch(function () use ($user, $code) {

            $message = NotificationMessageParser::init($user)
                ->customerMessage("KUFA, You:CODE", ['CODE' => $code])
                ->parse();
//
//            SMS::to($user->phone)
//                ->message($message[app()->getLocale()])
//                ->send();
//            Mail::to($user)->send(new VerifyPhoneMail($message[app()->getLocale()]));

        })->afterResponse();

    }

}
