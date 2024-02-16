<?php

namespace Tasawk\Actions\Customer;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Tasawk\Actions\Shared\Authentication\SendVerificationCode;
use Tasawk\Exceptions\APIException;
use Tasawk\Models\Customer;


class CustomerHasRightsToLogin
{
    use AsAction;


    /**
     * @throws Exception
     */
    public function handle()
    {
        $this->hasRoleCustomer()
            //            ->inBlackList()
            ->isPhoneVerified();
    }

    /**
     * @throws Exception
     */
    public function hasRoleCustomer(): CustomerHasRightsToLogin
    {
        $user = Customer::where('phone', request()->get('phone'))->first();
        if (!$user) {
            if (!$user->hasRole('customer')) {
                throw new APIException(__('Cant login as customer'));
            }
        }
        return $this;
    }

    /**
     * @throws Exception
     */
    public function inBlackList(): CustomerHasRightsToLogin
    {
        if (auth()->user()->blackList === 1) {
            throw new APIException(__('validation.api.account_suspend'));
        }
        return $this;
    }

    /**
     * @throws Exception
     */
    public function isPhoneVerified(): CustomerHasRightsToLogin
    {
        $user = Customer::where('phone', request()->get('phone'))->first();
        if ($user) {
            SendVerificationCode::run($user);
        }
        return $this;
    }

    /**
     * @throws Exception
     */
    //    public function isConfirmed(): CustomerHasRightsToLogin {
    //        if (auth()->user()->admin_confirmed == 0) {
    //            throw new AccountNotApprovedException();
    //        }
    //        return $this;
    //    }

}
