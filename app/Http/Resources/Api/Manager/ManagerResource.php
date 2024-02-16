<?php

namespace Tasawk\Http\Resources\Api\Manager;

use Illuminate\Http\Resources\Json\JsonResource;

class ManagerResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    public function toArray($request) {
        return [
            'id' => $this->id,
            'avatar'=> $this->getFirstMediaUrl(),
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'api_token' => $this->api_token,
            'notification_status' => $this->settings['notification_status'] ?? 1,
            'preferred_language' => $this->settings['preferred_language'] ?? 'ar',
            "phone_verified" => (int)!is_null($this->phone_verified_at),
        ];
    }
}
