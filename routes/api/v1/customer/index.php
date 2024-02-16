<?php
use Tasawk\Api\V1\Customer\ProductServices;

Route::prefix('v1')->group(function () {
    require_once __DIR__ . '/auth.php';
    require_once __DIR__ . '/profile.php';
    Route::get('products', [ProductServices::class, 'index']);
    Route::get('products/{product}', [ProductServices::class, 'show']);
    Route::post('products/{product}/favorite', [ProductServices::class, 'toggleFavorite'])->middleware('auth:sanctum');

    Route::group([], function () {


    });
});
