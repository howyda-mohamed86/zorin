<?php

namespace Tasawk\Http\Requests\Api\Manager\Authentication;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Tasawk\Ecommerce\Rules\IsValidVerificationCodeRule;
use Tasawk\Ecommerce\Rules\KSAPhoneRule;
use Tasawk\Ecommerce\Rules\ManagerPhoneExistRule;

class ResetPasswordRequest extends FormRequest {

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
            'phone' => ['required',new KSAPhoneRule(),new ManagerPhoneExistRule()],
            'code' => ['required', 'numeric','digits:5', new IsValidVerificationCodeRule()],
            'password' => ['required','confirmed',
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
