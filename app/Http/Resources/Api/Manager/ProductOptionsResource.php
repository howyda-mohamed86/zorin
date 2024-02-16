<?php

namespace Tasawk\Http\Resources\Api\Manager;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductOptionsResource extends JsonResource {


    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->option->option->name,
            'status' => $this->status,
            'values'=>ProductOptionValueResource::collection($this->values),
        ];
    }
}
