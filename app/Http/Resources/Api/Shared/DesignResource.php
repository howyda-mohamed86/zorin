<?php

namespace Tasawk\Http\Resources\Api\Shared;

use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource {

    public function toArray($request) {
        return [
            'id' => $this->id,
            'image' => $this->getFirstMediaUrl(),

        ];
    }
}
