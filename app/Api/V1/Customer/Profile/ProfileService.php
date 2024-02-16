<?php

namespace Tasawk\Api\V1\Customer\Profile;

use Illuminate\Http\Request;
use Tasawk\Actions\Shared\Authentication\ChangeUserPhone;
use Tasawk\Actions\Shared\Authentication\RemoveVerficationCodes;
use Tasawk\Actions\Shared\Authentication\UpdateCustomerProfile;
use Tasawk\Actions\Shared\Authentication\UpdateUserPassword;
use Tasawk\Actions\Shared\Authentication\UpdateUserToken;
use Tasawk\Api\Core;
use Tasawk\Api\Facade\Api;
use Tasawk\Http\Requests\Api\Customer\Profile\ProfileSettingRequest;
use Tasawk\Http\Requests\Api\Customer\Profile\UpdateCustomerProfileRequest;
use Tasawk\Http\Requests\Api\Customer\Profile\UpdatePasswordRequest;
use Tasawk\Http\Requests\Api\Customer\Profile\VerifyAltPhoneRequest;
use Tasawk\Http\Resources\Api\Customer\CustomerResource;
use Tasawk\Http\Resources\Api\Customer\Products\LightProductResource;
use Tasawk\Http\Resources\Api\Customer\User\ReportResources;
use Tasawk\Models\Catalog\Product;

class ProfileService {
    public function index() {
        return Api::isOk(__("Customer information"))->setData(new CustomerResource(auth()->user()));
    }

    public function update(UpdateCustomerProfileRequest $request) {
        UpdateCustomerProfile::run($request);
        if (auth()->user()->phone !== $request->get('phone')) {
            ChangeUserPhone::run(auth()->user(), $request->get('phone'));
        }
        return Api::isOk(__("Account information updated"))->setData(new CustomerResource(auth()->user()));
    }

    public function updatePassword(UpdatePasswordRequest $request) {
        UpdateUserPassword::run(auth()->user(), $request->get('password'));
        return Api::isOk(__("Account information updated"))->setData(new CustomerResource(auth()->user()));
    }

    public function settings(ProfileSettingRequest $request) {
        auth()->user()->update(['settings' => $request->validated()]);
        return Api::isOk(__("User settings updated"));
    }


    public function verifyAltPhone(VerifyAltPhoneRequest $request) {
        auth()->user()->update(['phone' => $request->get('phone')]);
        RemoveVerficationCodes::run(auth()->user());
        UpdateUserToken::run(auth()->user());
        return Api::isOk(__("Phone verified, try to login"));
    }


    public function reports() {
        return ReportResources::collection(auth()->user()->orders()->paid()->latest()->get());
    }

    public function wishlist(): Core {
        return Api::isOk(__("Product information"), LightProductResource::collection(auth()->user()->favorite(Product::class)));

    }
}
