<?php

namespace Tasawk\Http\Resources\Api\Customer\Cart;

use Arr;
use Cknow\Money\Money;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Tasawk\Models\Design\Design;


class CartProductResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request) {

        return Arr::get($this->attributes, 'group_type') == 'brand'
            ?
            [
                'id' => $this->associatedModel?->id,
                'image' => $this->associatedModel?->image,

                "name" => $this->associatedModel->title[app()->getLocale()] ?? $this->associatedModel->title[app()->getLocale() == 'ar' ? 'en' : 'ar'],
                "quantity" => $this->quantity,

                'price' => Money::parse($this->price)->format(),
                'price_sum' => Money::parse($this->getPriceSumWithConditions())->format()
            ]
            : [
                'dimensions' => Arr::get($this->attributes, 'dimensions', []),
                'design_id' => Arr::get($this->attributes, 'design_id', []),
                'design_url' => Design::where('id', Arr::get($this->attributes, 'design_id', null))->first()?->getFirstMediaUrl(),
                'value_id'=>Arr::get($this->attributes, 'value_id', [])
            ];
    }

}
