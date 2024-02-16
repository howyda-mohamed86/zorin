<?php

namespace Tasawk\Http\Requests\Api\Customer\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tasawk\Lib\Utils;
use Tasawk\Rules\AddressBelongToAuthUserRule;
use Tasawk\Rules\IsProductAvailableInBranchRule;
use Tasawk\Rules\IsRequiredProductOptionsRepresentRule;
use Tasawk\Rules\IsValidProductOptionsRule;
use Tasawk\Rules\IsValidProductOptionValuesRule;


class OrderRateRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            "rate" => ['required','array','size:3'],
            "rate.*" => ['required', 'numeric', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:512']
        ];
    }

}
