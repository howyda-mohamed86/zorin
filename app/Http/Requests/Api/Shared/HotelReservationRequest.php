<?php

namespace Tasawk\Http\Requests\Api\Shared;

use Illuminate\Foundation\Http\FormRequest;

class HotelReservationRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'hotel_id' => 'required|exists:hotels,id',
            'hotel_service_id' => 'required|exists:hotel_services,id,hotel_id,' . $this->hotel_id,
            'month' => 'required',
            'from' => 'required|integer',
            'to' => 'required|integer',
            'payment_method' => 'required|in:debit,MyFatoorah',
        ];
    }
}
