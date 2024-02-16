<?php

namespace Tasawk\Actions\Shared\Authentication;

use Lorisleiva\Actions\Concerns\AsAction;
use Tasawk\Models\Manager;
use Tasawk\Models\User;


class UpdateUserToken {
    use AsAction;

    public function handle(User|Manager $user): bool {
        $user->tokens()->delete();
        $user->update(['api_token'=>$user->createToken("Tasawk:Token")->plainTextToken,'phone_verified_at' => now()]);
        return true;
    }

}
