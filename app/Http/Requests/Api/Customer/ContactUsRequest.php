<?php

namespace Tasawk\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Tasawk\Rules\KSAPhoneRule;

class ContactUsRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            "name" => ["required_if:guest,true"],
            "email" => ["required_if:guest,true", "email"],
            "phone" => ["required_if:guest,true", new KSAPhoneRule],
            "message" => ["required", "min:25"],
            'subject' => [],
            'user_id' => [],
            'guest' => ['required', 'boolean']
        ];
    }

    protected function prepareForValidation() {

        $this->merge([
            "subject" => '',
            'user_id' => !$this->boolean('guest') ? request()->user('sanctum')?->id : null,
        ]);
    }

    public function attributes() {
        return [
            'name' => __('name'),
            'phone' => __('phone'),
            'email' => __('email'),

            'message' => __('message'),
            'subject' => __('subject'),
            'contact_type_id' => __('contact type'),
        ];
    }
}
