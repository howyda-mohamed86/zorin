<?php

namespace Tasawk\Api\V1\Shared;

use Tasawk\Api\Facade\Api;
use Tasawk\Http\Resources\Api\Shared\BannerResource;
use Tasawk\Models\Content\Banner;

class BannerServices  {
    public function list() {
        $banners = Banner::enabled()->latest()->get();
        return Api::isOk(__("List of banners"), BannerResource::collection($banners));
    }




}
