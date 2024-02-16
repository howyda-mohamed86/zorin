<?php

namespace Tasawk\Http\Requests\Api\Manager\Authentication;

use Illuminate\Foundation\Http\FormRequest;
use Tasawk\Rules\KSAPhoneRule;

class LoginRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    protected function prepareForValidation() {

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'phone' => ['required', new KSAPhoneRule()],
            'password' => [
                'required',
            ],
        ];
    }


}
