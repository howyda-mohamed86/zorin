<?php

namespace Tasawk\Http\Requests\Api\Customer\Profile;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tasawk\Rules\KSAPhoneRule;

class UpdateCustomerProfileRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => $this->get('full_name')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                Rule::unique('users')->ignore(auth()->id()),
                new KSAPhoneRule(),
            ],
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],
            'gender' => ['required', 'in:male,female'],
            'birth_date' => ['required', 'date'],

        ];
    }
}
