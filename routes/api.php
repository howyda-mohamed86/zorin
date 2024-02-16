<?php

use Tasawk\Notifications\SendAdminMessagesNotification;
use Tasawk\Settings\ThirdPartySettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

require_once __DIR__ . '/api/v1/shared/index.php';
require_once __DIR__ . '/api/v1/customer/index.php';

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



