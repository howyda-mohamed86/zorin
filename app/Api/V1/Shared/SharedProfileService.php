<?php

namespace Tasawk\Api\V1\Shared;

use Tasawk\Api\Core;
use Tasawk\Api\Facade\Api;
use Tasawk\Http\Requests\Api\Shared\UpdateUserDeviceTokenRequest;


class SharedProfileService {
    public function destroy() {
        auth()->user()->delete();
        auth()->user()->tokens()->delete();
        return Api::isOk(__("User account deleted"));
    }


    public function updateDeviceToken(UpdateUserDeviceTokenRequest $request): Core {
        auth()->user()->deviceTokens()->create($request->validated());
        return Api::isOk(__("Token updated"));
    }

    public function deleteAccount(): Core {
        auth()->user()->update([
            'email' => auth()->user()->email . '[deleted]',
            'phone' => auth()->user()->phone . '[deleted]',
            'account_deleted_at' => now(),
        ]);
        auth()->user()->delete();
        auth()->user()->tokens()->delete();
        return Api::isOk(__("Account deleted"));
    }


}
