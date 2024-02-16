<?php

namespace Tasawk\Http\Resources\Api\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use Tasawk\Brands\Resources\Api\BrandResources;
use Tasawk\Fleet\Http\Resources\DriverResource;
use Tasawk\Jobs\Http\Resources\Api\RateResources;
use Tasawk\Orders\Resources\Api\AddressOrderResource;
use Tasawk\Orders\Resources\Api\UserOrderResource;

class RateResource extends JsonResource {

    public function toArray($request) {
        return [
            "score" => collect($this->rate),
            "comment" => $this->comment,
        ];
    }
}
