<?php

use Tasawk\Api\V1\Customer\Profile\AddressBookService;
use Tasawk\Api\V1\Customer\Profile\OrderService;
use Tasawk\Api\V1\Customer\Profile\ProfileService;
use Tasawk\Http\Middleware\EnsureThatAddressBelongToAuthUserMiddleware;
use Tasawk\Http\Middleware\EnsureThatOrderDeliveredMiddleware;
use Tasawk\Http\Middleware\EnsureThatOrderNotRatedBeforeMiddleware;

Route::middleware(["auth:sanctum"])->group(function () {

    Route::get('profile', [ProfileService::class, 'index']);

    Route::post('profile', [ProfileService::class, 'update']);

    Route::post('profile/update-password', [ProfileService::class, 'updatePassword']);

    Route::get('profile/reports', [ProfileService::class, 'reports']);

    Route::get('profile/wishlist', [ProfileService::class, 'wishlist']);

    Route::post('profile/settings', [ProfileService::class, 'settings']);

    Route::post('verify-alt-phone', [ProfileService::class, 'verifyAltPhone']);

    Route::get('profile/addresses', [AddressBookService::class, 'index']);
    Route::get('profile/addresses/{address}', [AddressBookService::class, 'show']);
    Route::post('profile/addresses', [AddressBookService::class, 'store']);
    Route::put('profile/addresses/{address}', [AddressBookService::class, 'update'])->middleware([EnsureThatAddressBelongToAuthUserMiddleware::class]);
    Route::delete('profile/addresses/{address}', [AddressBookService::class, 'destroy'])->middleware([EnsureThatAddressBelongToAuthUserMiddleware::class]);
    Route::get('profile/orders', [OrderService::class, 'index']);
    Route::get('profile/orders/{order}', [OrderService::class, 'show']);
    Route::get('profile/orders/{order}/private', [OrderService::class, 'showPrivateOrder']);

    Route::post('profile/orders/{order}/offers/{offer}/accept', [OrderService::class, 'acceptOffer'])
        ->scopeBindings();

    Route::post('profile/orders/{order}/rate', [OrderService::class, 'rate'])->middleware([EnsureThatOrderNotRatedBeforeMiddleware::class, EnsureThatOrderDeliveredMiddleware::class]);


});
