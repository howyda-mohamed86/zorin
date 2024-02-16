<?php

use Tasawk\Api\V1\Shared\SettingServices;

Route::get('settings/contacts/types', [SettingServices::class, 'contactTypes']);
Route::get('settings', [SettingServices::class, 'all']);
//Route::get('settings/working-times', [SettingServices::class, 'workingTimes']);
//Route::get('settings/social-media', [SettingServices::class, 'socialMedia']);
