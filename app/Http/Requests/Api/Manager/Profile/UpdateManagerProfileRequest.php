<?php

namespace Tasawk\Http\Requests\Api\Manager\Profile;

use App\Actions\SendVerificationCode;
use App\Rules\CheckPhoneFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tasawk\Ecommerce\Rules\KSAPhoneRule;

class UpdateManagerProfileRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    protected function prepareForValidation() {
        $this->merge(['name' => $this->get('full_name')]);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
