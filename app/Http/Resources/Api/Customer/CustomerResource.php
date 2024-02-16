<?php

namespace Tasawk\Http\Resources\Api\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'avatar' => $this->getFirstMediaUrl(),
            'user_name' => $this->name . ' ' . $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender == 'male' ? __('forms.fields.male') : __('forms.fields.female'),
            'birth_date' => $this->birth_date,
            'balance' => $this->balance,
            'api_token' => $this->api_token,
            'notification_status' => $this->settings['notification_status'] ?? 1,
            'preferred_language' => $this->settings['preferred_language'] ?? 'ar',
            "phone_verified" => (int)!is_null($this->phone_verified_at),
        ];
    }
}
