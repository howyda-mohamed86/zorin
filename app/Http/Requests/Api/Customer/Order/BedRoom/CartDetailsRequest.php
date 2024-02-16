<?php

namespace Tasawk\Http\Requests\Api\Customer\Order\BedRoom;

use Illuminate\Foundation\Http\FormRequest;
use Tasawk\Rules\IsValidBedRoomItemsRule;
use Tasawk\Rules\IsValidKitchenItemsRule;
use Tasawk\Rules\IsValidOfficesItemsRule;


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
            'items' => ['required', 'array', new IsValidBedRoomItemsRule()]
        ];
    }

}
