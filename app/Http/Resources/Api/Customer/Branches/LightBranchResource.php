<?php

namespace Tasawk\Http\Resources\Api\Customer\Branches;

use Illuminate\Http\Resources\Json\JsonResource;
use Tasawk\Api\V1\Customer\BranchServices;
use Tasawk\Lib\Utils;

class LightBranchResource extends JsonResource {


    public function toArray($request): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            $this->mergeWhen($request->anyFilled(['address_id', 'coordinate']), [
                'distance_db' => Utils::formatDistance($this->distance)
            ]),
            'status'=> $this->availableNow()? 'open' : 'closed',
            $this->mergeWhen(!$this->availableNow(),[
                'working_period' => $this->todayPeriods(),
            ])

        ];
    }
}
