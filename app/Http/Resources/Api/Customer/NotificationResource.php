<?php

namespace Tasawk\Http\Resources\Api\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {

        return [
            'id' => $this->id,
            "title" => $this->title,
            "description" => $this->body,
            'created_date' => $this->created_at->toDateTimeString(),
            'formatted_date' => $this->created_at->format("Y-m-d h:i a"),
            'data' => isset($this->data['data']) ? (object)$this->data['data'] : (object)[],
            'read_at' => $this->read_at,
        ];
    }
}
