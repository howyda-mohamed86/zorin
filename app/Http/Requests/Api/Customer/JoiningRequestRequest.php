<?php

namespace Tasawk\Http\Requests\Api\Customer;


use Illuminate\Foundation\Http\FormRequest;

class JoiningRequestRequest extends FormRequest {

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
            'name' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
            'dob' => ['required'],
            'residence' => ['required'],
            'national_id' => ['required','image'],
            'experience'=>['required','numeric'],
            'qualification'=>['required','file'],
            'attachments'=>['required','array'],
            'attachments.*'=>['required','file'],
            'cv'=>['required','file'],
            'notes'=>['nullable','string'],


        ];
    }


}
