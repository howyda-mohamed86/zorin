<?php

namespace Tasawk\Http\Resources\Api\Shared;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->description,
            'image' => $this->getFirstMediaUrl(),
            'publish_date' => $this->publish_date->format('Y-m-d h:i a'),
        ];
    }
}
