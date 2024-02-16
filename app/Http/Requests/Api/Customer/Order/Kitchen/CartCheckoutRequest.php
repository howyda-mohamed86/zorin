<?php

namespace Tasawk\Http\Requests\Api\Customer\Order\Kitchen;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tasawk\Lib\Utils;

;

use Tasawk\Rules\IsValidKitchenItemsRule;
use Tasawk\Settings\GeneralSettings;


class CartCheckoutRequest extends FormRequest {

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
            'design_id' => ['required', Rule::exists('designs',"id")->where('status', 1)],
            'dimensions' => ['required', 'array'],
            'dimensions.height' => ['required', 'numeric'],
            'dimensions.width' => ['required', 'numeric'],
            'dimensions.length' => ['required', 'numeric'],
            'notes' => ['nullable'],
            'lock_kitchen' => ['required'],
            'delivery_method'=>['required','in:mail,whatsapp'],
            'items' => ['required', 'array', new IsValidKitchenItemsRule],
            'attachments'=>['nullable','array'],
            'attachments.*'=>['required','image'],
            'images'=>['nullable','array'],
            'images.*'=>['required','image'],

        ];
    }

}
