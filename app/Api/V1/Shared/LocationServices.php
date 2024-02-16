<?php

namespace Tasawk\Api\V1\Shared;
use Tasawk\Api\Facade\Api;
use Tasawk\Http\Resources\Api\Shared\ZoneResource;
use Tasawk\Models\Zone;

class LocationServices {
    public function zones() {
        return Api::isOk("Zones list", ZoneResource::collection(Zone::enabled()->get()));
    }


}
