<?php

namespace Tasawk\Api\V1\Providers;

use Tasawk\Api\Facade\Api;
use Tasawk\Http\Resources\Api\Shared\PostResource;
use Tasawk\Models\Content\Post;
use Tasawk\Http\Requests\Api\Providers\AddServiceProviderRequest;
use Tasawk\Models\ServiceProviderRequest;

class ServiceProviderRequestServices
{
    public function addRequest(AddServiceProviderRequest $request)
    {
        $provider = ServiceProviderRequest::create($request->only('name', 'phone', 'email', 'password', 'iban', 'national_id', 'national_type'));
        if ($request->avatar) {
            $provider->addMedia($request->avatar)->toMediaCollection('default');
        }
        if ($request->commercial_register) {
            $provider->addMedia($request->commercial_register)->toMediaCollection('commercial_register');
        }
        return Api::isOk(__("Request added successfully"));
    }
}
