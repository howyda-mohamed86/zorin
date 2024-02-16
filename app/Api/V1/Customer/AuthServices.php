<?php

namespace Tasawk\Api\V1\Customer;

use Api;
use Illuminate\Support\Facades\Auth;
use Tasawk\Actions\Customer\CustomerHasRightsToLogin;
use Tasawk\Actions\Customer\FireWelcomeMessageAction;
use Tasawk\Actions\Customer\RegisterCustomer;
use Tasawk\Actions\Shared\Authentication\ForgetPassword;
use Tasawk\Actions\Shared\Authentication\SendVerificationCode;
use Tasawk\Actions\Shared\Authentication\UpdateUserPassword;
use Tasawk\Actions\Shared\Authentication\UpdateUserToken;
use Tasawk\Actions\Shared\Authentication\VerifyUserAccount;
use Tasawk\Api\Core;
use Tasawk\Http\Requests\Api\Customer\Auth\CodeConfirmRequest;
use Tasawk\Http\Requests\Api\Customer\Auth\ForgetPasswordRequest;
use Tasawk\Http\Requests\Api\Customer\Auth\LoginRequest;
use Tasawk\Http\Requests\Api\Customer\Auth\RegisterCustomerRequest;
use Tasawk\Http\Requests\Api\Customer\Auth\ResetPasswordRequest;
use Tasawk\Http\Requests\Api\Customer\Auth\SendOTPRequest;
use Tasawk\Http\Requests\Api\Customer\Auth\VerifyAccountRequest;
use Tasawk\Http\Resources\Api\Customer\CustomerResource;
use Tasawk\Models\Customer;

class AuthServices
{

    public function login(LoginRequest $request)
    {
        $user = Customer::where('phone', $request->get('phone'))->first();
        if (!$user) {
            return Api::isError(__('validation.api.invalid_credentials'))->setErrors(['credentials' => __('validation.api.invalid_credentials')]);
        }
        CustomerHasRightsToLogin::run();
        UpdateUserToken::run($user, $request->get('device_token'));
        return Api::isOk(__("Log IN process succeed,SMS Code sent"))->setData(['phone' => $user->phone, 'code' => $user->verificationCodes->first()->code]);
    }


    public function verifySMSCode(CodeConfirmRequest $request): Core
    {
        return Api::isOk(__("Verified,User information"))->setData(new CustomerResource($request->currentUser()));
    }

    public function register(RegisterCustomerRequest $request)
    {
        $customer = RegisterCustomer::run(...$request->only("first_name", "last_name", "gender", "birth_date", 'email', 'phone', 'device_token'));
        if ($request->hasFile("avatar")) {
            $customer->addMedia($request->file("avatar"))->toMediaCollection();
        }
        SendVerificationCode::run($customer);
        return Api::isOk(__("Registration process succeed,SMS Code sent"))->setData([
            'phone' => $customer->phone,
            'code' => $customer->verificationCodes->first()->code]);
    }

    public function verify(VerifyAccountRequest $request)
    {
        VerifyUserAccount::run($request->currentUser());
        return Api::isOk(__("Verified,User information"))->setData(new CustomerResource($request->currentUser()));
    }

    public function forgetPassword(ForgetPasswordRequest $request): Core
    {
        ForgetPassword::run($request->currentUser());
        return Api::isOk(__("SMS code sent"));
    }

    public function resetPassword(ResetPasswordRequest $request): Core
    {
        UpdateUserPassword::run($request->currentUser(), $request->get('password'));
        return Api::isOk(__("User information"))->setData(new CustomerResource($request->currentUser()));
    }

    public function sendOTP(SendOTPRequest $request): Core
    {
        $code = 1234;
        auth()->user()->verificationCodes()->delete();
        auth()->user()->verificationCodes()->create(['phone' => $request->get('phone'), "code" => $code]);
        return Api::isOk(__("OTP sent"))->setData([]);
    }
}
