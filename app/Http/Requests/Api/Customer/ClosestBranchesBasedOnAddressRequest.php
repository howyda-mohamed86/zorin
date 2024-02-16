<?php

namespace Tasawk\Http\Requests\Api\Customer;


use Illuminate\Foundation\Http\FormRequest;
use Tasawk\Models\AddressBook;

class ClosestBranchesBasedOnAddressRequest extends FormRequest {

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
            'address_id' => ['required',
                function ($attr, $value, $fail) {
                    if (!auth()->user()?->addresses()->where('id', $value)->exists()) {
                        $fail(__('validation.api.invalid_address'));
                    }
                }
            ],

        ];
    }

    protected function passedValidation() {
        $this->merge(['address_id' => AddressBook::find($this->address_id)]);
    }
}
