<?php

namespace Tasawk\Http\Requests\Api\Providers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Tasawk\Rules\KSAPhoneRule;

class AddServiceProviderRequest extends FormRequest
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
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'name' => ['required', 'string', 'min:3', 'max:40'],
            'phone' => [
                'required',
                'unique:service_provider_requests',
                new KSAPhoneRule()
            ],
            'email' => ['required', 'email', 'unique:service_provider_requests'],
            'password' => [
                'required',
            ],
            'password_confirmation' => ['required', 'same:password'],
            'iban' => ['required'],
            'national_id' => ['required'],
            'national_type' => ['required', 'in:citizen,resident'],
            'commercial_register' => ['nullable'],
            'is_approved_terms' => ['required', 'in:1'],
        ];
    }
}
