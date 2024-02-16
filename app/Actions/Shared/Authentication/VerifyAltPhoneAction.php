<?php

namespace Tasawk\Actions\Shared\Authentication;


use Lorisleiva\Actions\Concerns\AsAction;

class VerifyAltPhoneAction {
    use AsAction;

    public function handle($request) {
        auth()->user()->update(['phone' => $request->get('phone')]);
        auth()->user()->verificationCodes()->delete();
    }

}
