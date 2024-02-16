<?php

namespace Tasawk\Http\Requests\Api\Customer;


use Illuminate\Foundation\Http\FormRequest;

class BranchesFilterRequest extends FormRequest {

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
            'sort_method' => ['nullable', 'in:coordinate'],
            'coordinate'=>['required_if:sort_method,coordinate'],

        ];
    }


}
