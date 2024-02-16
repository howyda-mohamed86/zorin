<?php

namespace Tasawk\Http\Requests\Api\Manager\Authentication;

use Illuminate\Foundation\Http\FormRequest;

use Tasawk\Rules\IsValidVerificationCodeRule;
use Tasawk\Rules\KSAPhoneRule;
use Tasawk\Rules\ManagerPhoneExistRule;

class CodeConfirmRequest extends FormRequest {

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
            'phone' => ['required', new ManagerPhoneExistRule, new KSAPhoneRule()],
            'code' => ['required', 'numeric', 'digits:5', new IsValidVerificationCodeRule()],
        ];
    }


}
