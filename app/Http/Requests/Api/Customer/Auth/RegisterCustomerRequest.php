<?php

namespace Tasawk\Http\Requests\Api\Customer\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Tasawk\Rules\KSAPhoneRule;

class RegisterCustomerRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'min:3', 'max:40'],
            'last_name' => ['required', 'string', 'min:3', 'max:40'],
            'phone' => [
                'required',
                'unique:users',
                new KSAPhoneRule()
            ],
            'avatar' => ['nullable'],
            'email' => ['required', 'email', 'unique:users'],
            'gender' => ['required', 'in:male,female'],
            'birth_date' => ['required', 'date'],
            'is_approved_conditions' => ['required', 'in:1'],
            'device_token' => ['required']

        ];
    }
}
