<?php

namespace Tasawk\Http\Requests\Api\Customer\Order\Kitchen;

use Illuminate\Foundation\Http\FormRequest;
use Tasawk\Rules\IsValidKitchenItemsRule;


class CartDetailsRequest extends FormRequest {
    protected $stopOnFirstFailure = true;

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
            'items' => ['required', 'array', new IsValidKitchenItemsRule],
        ];
    }

}
