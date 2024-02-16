<?php

namespace Tasawk\Http\Requests\Api\Customer\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Tasawk\Models\User;
use Tasawk\Rules\IsValidVerificationCodeRule;
use Tasawk\Rules\KSAPhoneRule;


class ResetPasswordRequest extends FormRequest {

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
            'phone' => ['required', new KSAPhoneRule()],
            'code' => ['required', 'numeric', 'digits:4', new IsValidVerificationCodeRule()],
            'password' => ['required', 'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()],
        ];
    }

    public function currentUser() {
        return User::where('phone', $this->get("phone"))->firstOrFail();
    }
}
