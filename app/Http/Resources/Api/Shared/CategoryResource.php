<?php

namespace Tasawk\Http\Resources\Api\Shared;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource {

    public function toArray($request) {
        return [
            'key' => $this->mapper_key,
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->getFirstMediaUrl(),
//            $this->mergeWhen($request->is())
            'brands'=>BrandResource::collection($this->brands)

        ];
    }
}
