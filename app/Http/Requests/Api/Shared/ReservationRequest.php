<?php

namespace Tasawk\Http\Requests\Api\Shared;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest {

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
        return[
            'type' => 'required|in:hotel,category',
            'category_id' => 'required_if:type,category|exists:categories,id',
            'hotel_id' => 'required_if:type,hotel|exists:hotels,id',
            'month' => 'required|date_format:m',
            'from' => 'required|integer',
            'to' => 'required|integer',
            'night_count' => 'required|integer',
            'payment_method' => 'required|in:debit,MyFatoorah',
        ];
    }

}
