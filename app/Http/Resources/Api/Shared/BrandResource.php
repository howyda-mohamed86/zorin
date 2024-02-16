<?php

namespace Tasawk\Http\Resources\Api\Shared;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource {

    public function toArray($request) {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->getFirstMediaUrl(),
            'product_count' => $this->products()->count(),

        ];
    }
}
