<?php

namespace Tasawk\Api\V1\Shared;

use Tasawk\Api\Facade\Api;
use Tasawk\Http\Requests\Api\Customer\ContactUsRequest;
use Tasawk\Http\Resources\Api\Shared\PageResource;
use Tasawk\Models\Content\Contact;
use Tasawk\Models\Content\Page;
use Tasawk\Settings\GeneralSettings;

class ContentServices {
    public function contact(ContactUsRequest $request) {
        Contact::create($request->validated());
        return Api::isOk(__("Message sent successfully"));
    }

    public function page($page, GeneralSettings $settings) {
        $mapper = match ($page) {
            'about' => $settings->app_pages['about_us'],
            'terms' => $settings->app_pages['terms_and_conditions'],
            'privacy' => $settings->app_pages['privacy_policy'],
            default => null,
        };
        return Api::isOk(__("Page information"),PageResource::make(Page::findOrFail($mapper)));
    }


}
