<?php

namespace Tasawk\Http\Requests\Api\Customer\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Tasawk\Rules\IsValidVerificationCodeRule;
use Tasawk\Rules\KSAPhoneRule;

class VerifyAltPhoneRequest extends FormRequest {

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
            'phone' => ['required', 'exists:verification_codes', new KSAPhoneRule()],
            'code' => ['required', 'numeric','digits:4', new IsValidVerificationCodeRule()],

        ];
    }

}
