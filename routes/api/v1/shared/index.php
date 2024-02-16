<?php

use Tasawk\Api\V1\Shared\ArticleServices;
use Tasawk\Api\V1\Shared\BannerServices;
use Tasawk\Api\V1\Shared\BrandServices;
use Tasawk\Api\V1\Shared\CategoryServices;
use Tasawk\Api\V1\Shared\ContentServices;
use Tasawk\Api\V1\Shared\LocationServices;
use Tasawk\Api\V1\Shared\NotificationServices;
use Tasawk\Api\V1\Shared\SharedProfileService;
use Tasawk\Http\Middleware\EnsureThatBranchPresentInRequestHeader;
use Tasawk\Api\V1\Providers\ServiceProviderRequestServices;
use Tasawk\Api\V1\Hotels\HotelServices;
use Tasawk\Api\V1\Shared\ReservationServices;

Route::prefix('v1')->group(function () {
    include __DIR__ . '/settings.php';


    Route::get('banners', [BannerServices::class, 'list']);
    Route::get('categories', [CategoryServices::class, 'list'])->where('type','design|product');
    Route::get('categories/{category}', [CategoryServices::class, 'show']);

    Route::get('categories/{category}/patterns', [CategoryServices::class, 'patterns']);
    Route::get('categories/{category}/products', [CategoryServices::class, 'products']);
    Route::get('categories/{category}/patterns/{pattern}/designs', [CategoryServices::class, 'designs']);
    Route::get('brands/{brand}/products', [BrandServices::class, 'products']);
    Route::get('brands/{brand}/products/{product}', [BrandServices::class, 'product']);

//    Route::get('categories/{category}', [CategoryServices::class, 'show']);

    Route::get('articles', [ArticleServices::class, 'list']);
    Route::get('articles/{article}', [ArticleServices::class, 'show']);

    Route::post('contacts', [ContentServices::class, 'contact']);
    Route::get('pages/{slug}', [ContentServices::class, 'page']);
    Route::get('locations/zones', [LocationServices::class, 'zones']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('users/device-token', [SharedProfileService::class, 'updateDeviceToken']);
        Route::delete('users/delete-account', [SharedProfileService::class, 'deleteAccount']);

        Route::get('users/notifications', [NotificationServices::class, 'all']);
        Route::delete('users/notifications/{id?}', [NotificationServices::class, 'destroy']);
        Route::post('users/notifications/fcm', [NotificationServices::class, 'fcm']);
    });



    Route::group(['prefix' => 'service-provider'], function () {
        Route::post('joining-request', [ServiceProviderRequestServices::class, 'addRequest']);
    });

    Route::group(['prefix' => 'hotels'],function(){
        Route::get('/', [HotelServices::class, 'list']);
        Route::get('details/{hotel}', [HotelServices::class, 'show']);
    });
    Route::post('booking', [ReservationServices::class, 'booking']);

});