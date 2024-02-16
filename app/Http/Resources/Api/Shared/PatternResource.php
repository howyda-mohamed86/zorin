<?php

namespace Tasawk\Http\Resources\Api\Shared;

use Illuminate\Http\Resources\Json\JsonResource;

class PatternResource extends JsonResource {

    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->getFirstMediaUrl(),

        ];
    }
}
