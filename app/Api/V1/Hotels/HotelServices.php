<?php

namespace Tasawk\Api\V1\Hotels;

use Tasawk\Api\Facade\Api;
use Tasawk\Models\Hotel;
use Tasawk\Http\Resources\Api\Hotel\HotelResource;

class HotelServices
{


    public function list()
    {
        $hotels = Hotel::where('status', 1)->with('hotelServices')->get();
        return Api::isOk(__("Hotel list"), HotelResource::collection($hotels));
    }

    public function show(Hotel $hotel)
    {
        $hotel = Hotel::where('status', 1)
            ->where('id', $hotel->id)
            ->with('hotelServices')->first();
        return Api::isOk(__("Hotel details"), HotelResource::make($hotel));
    }
}
