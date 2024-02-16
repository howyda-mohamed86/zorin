<?php

namespace Tasawk\Http\Requests\Api\Customer\Order\Private;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tasawk\Lib\Utils;

;

use Tasawk\Rules\IsValidBedRoomItemsRule;
use Tasawk\Rules\IsValidKitchenItemsRule;
use Tasawk\Rules\IsValidOfficesItemsRule;
use Tasawk\Settings\GeneralSettings;


class ActionsCartCheckoutRequest extends FormRequest {

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
            'place_name'=>['required','string','min:3','max:512'],
            'location'=>['required','array'],
            'location.lat'=>['required','numeric'],
            'location.lng'=>['required','numeric'],
            'notes'=>['nullable','string','min:3','max:512'],
        ];
    }

}
