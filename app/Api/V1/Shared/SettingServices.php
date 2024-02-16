<?php

namespace Tasawk\Api\V1\Shared;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Str;
use Tasawk\Api\Core;
use Tasawk\Api\Facade\Api;
use Tasawk\Http\Resources\Api\Shared\ContactTypeResource;
use Tasawk\Models\ContactType;
use Tasawk\Settings\GeneralSettings;


class SettingServices {
    public function all(GeneralSettings $settings) {
        return Api::isOk(__("Settings list"), [
            "name" => $settings->app_name,
            'email' => $settings->app_email,
            'address' => $settings->app_address,
            "phone" => $settings->app_phone,
            'social_media' => $this->socialMedia($settings->social_links),
        ]);
    }


    public function socialMedia($links): Collection {
        return collect($links)->map(function ($el) {
            $el['id'] = Str::between($el['icon'], '-', '-');
            return $el;
        })->pluck('link', 'id');

    }


    public function contactTypes() {
        return Api::isOk("Types List")->setData(ContactTypeResource::collection(ContactType::enabled()->latest()->paginate()));
    }


}
