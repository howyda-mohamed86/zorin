<?php

namespace Tasawk\Http\Requests\Api\Customer\Profile;

use Arr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tasawk\Rules\KSAPhoneRule;

class AddressBookRequest extends FormRequest {

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
            "name" => ["required", "string", "max:255"],
            "phone" => ["required", "numeric", new KSAPhoneRule],
            "zone_id" => ["required", "numeric", Rule::exists('zones', "id")],
            "map_location" => ["required"],
            "map_location.lat" => ["required", 'numeric'],
            "map_location.lng" => ["required", 'numeric'],
            "state" => ["required", "string", "max:255"],
            "street" => ["sometimes", "string", "max:255"],
            "building_number" => ['nullable', "numeric"],
            "floor" => ['nullable', "numeric"],
            "notes" => ["required", "string"],
            'primary' => ['required', 'boolean'],
        ];
    }

    public function prepareForValidation() {
        $this->merge([
            'map_location' => [
                'lat' => (float)Arr::get($this?->map_location,'lat',''),
                'lng' => (float)Arr::get($this?->map_location,'lng','')
            ]
        ]);
    }
}
