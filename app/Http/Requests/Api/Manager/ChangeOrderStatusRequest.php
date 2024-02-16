<?php

namespace Tasawk\Http\Requests\Api\Manager;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Str;
use Tasawk\Enum\OrderStatus;
use Tasawk\Rules\IsValidVerificationCodeRule;
use Tasawk\Rules\KSAPhoneRule;
use Tasawk\Rules\ManagerPhoneExistRule;

class ChangeOrderStatusRequest extends FormRequest {

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
            'status' => ['required', function ($attribute, $value, $fail) {
                if ($this->isCancelStatus() && $this->route('order')->status->value == OrderStatus::PENDING_FOR_ACCEPT_ORDER->value) {
                    return true;
                }
                if (!in_array($value, $this->route('order')->getAvailableStatus()->pluck('value')->toArray())) {
                    $fail(__("validation.api.invalid_status"));
                    return false;
                }


            }],
        ];
    }

    public function isCancelStatus(): bool {
        return Str::of($this->get('status'))->contains("cancel-");
    }
}
