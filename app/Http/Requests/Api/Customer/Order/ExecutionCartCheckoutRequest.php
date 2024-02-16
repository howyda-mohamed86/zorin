<?php

namespace Tasawk\Http\Requests\Api\Customer\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tasawk\Lib\Utils;

;

use Tasawk\Rules\IsValidBedRoomItemsRule;
use Tasawk\Rules\IsValidKitchenItemsRule;
use Tasawk\Rules\IsValidOfficesItemsRule;
use Tasawk\Settings\GeneralSettings;


class ExecutionCartCheckoutRequest extends FormRequest {

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
            'place_type' => ['required'],
            'order_id' => ['required_without:attachments', Rule::exists('orders','id')->where('order_type', 'design')->where('place_type', $this->get('place_type'))],
            'attachments' => ['required_without:order_id', 'array'],
            'attachments.*' => ['required', 'image'],
            'notes' => ['nullable', 'string', 'min:3', 'max:512'],
        ];
    }

}
