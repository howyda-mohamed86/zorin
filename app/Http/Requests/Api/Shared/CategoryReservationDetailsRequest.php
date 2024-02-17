<?php

namespace Tasawk\Http\Requests\Api\Shared;

use Illuminate\Foundation\Http\FormRequest;

class CategoryReservationDetailsRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'individual_service_id' => 'required|exists:individual_services,id,category_id,' . $this->category_id,
            'month' => 'required',
            'from' => 'required|integer',
            'to' => 'required|integer',
        ];
    }
}
