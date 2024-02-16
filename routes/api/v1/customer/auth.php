<?php

use Tasawk\Api\V1\Customer\AuthServices;

Route::post('auth/login', [AuthServices::class,'login']);
Route::post('auth/register', [AuthServices::class,'register']);
Route::post('auth/verify-account', [AuthServices::class,'verify']);
Route::post('auth/forget-password', [AuthServices::class,'forgetPassword']);
Route::post('auth/reset-password',[AuthServices::class,'resetPassword']);
Route::post('auth/verify-sms-code', [AuthServices::class,'verifySMSCode']);

