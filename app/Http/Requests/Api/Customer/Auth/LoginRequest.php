<?php

namespace Tasawk\Http\Requests\Api\Customer\Auth;

use App\Rules\CheckPhoneFormat;
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
        if (!$this->filled('country_code')) {
            $this->merge(['country_code' => "966"]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'phone' => ['required', new KSAPhoneRule()],
            'country_code' => ['required']
        ];
    }


}
