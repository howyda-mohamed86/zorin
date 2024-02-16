<?php

namespace Tasawk\Http\Requests\Api\Customer\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Tasawk\Models\User;
use Tasawk\Rules\IsValidVerificationCodeRule;
use Tasawk\Rules\KSAPhoneRule;

class VerifyAccountRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'phone' => ['required', 'exists:users',new KSAPhoneRule()],

            'code' => ['required', 'numeric','digits:4', new IsValidVerificationCodeRule()],
        ];
    }

    public function currentUser() {
        return User::where('phone', $this->get("phone"))->firstOrFail();
    }
}
