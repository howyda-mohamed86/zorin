<?php

namespace Tasawk\Api\V1\Customer\Profile;


use Api;
use Tasawk\Http\Requests\Api\Customer\Profile\AddressBookRequest;
use Tasawk\Http\Resources\Api\Customer\AddressBookResource;
use Tasawk\Models\AddressBook;

class AddressBookService{
    public function index() {
        return Api::isOk(__("Addresses list"),AddressBookResource::collection(auth()->user()->addresses()->latest()->paginate()));
    }

    public function show(AddressBook $address) {
        return Api::isOk(__("Address data"),new AddressBookResource($address));
    }

    public function store(AddressBookRequest $request) {
        $address = auth()->user()->addresses()->create($request->validated());
        return Api::isOk(__("Address created"),new AddressBookResource($address));
    }

    public function update(AddressBookRequest $request, AddressBook $address) {
        $address->update($request->validated());
        return Api::isOk(__("Address updated"), new AddressBookResource($address));
    }

    public function destroy(AddressBook $address) {
        if (!in_array($address->id, auth()->user()->addresses->pluck('id')->toArray())) {
            return Api::isError(__("Unauthorized action"));
        }
        $address->delete();
        return Api::isOk(__("Address deleted"));
    }
}
