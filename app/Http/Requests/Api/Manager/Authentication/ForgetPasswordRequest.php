<?php

namespace Tasawk\Http\Requests\Api\Manager\Authentication;

use Illuminate\Foundation\Http\FormRequest;
use Tasawk\Models\User;
use Tasawk\Rules\KSAPhoneRule;
use Tasawk\Rules\ManagerPhoneExistRule;

class ForgetPasswordRequest extends FormRequest {

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
            'phone' => ['required', new KSAPhoneRule,new ManagerPhoneExistRule()],
        ];
    }

    public function currentUser() {
        return User::where('phone', $this->get("phone"))->firstOrFail();
    }


}
