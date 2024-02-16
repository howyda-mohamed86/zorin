<?php

namespace Tasawk\Http\Resources\Api\Customer\Products;

use Illuminate\Http\Resources\Json\JsonResource;
use Tasawk\Lib\Utils;

class ProductResource extends JsonResource {


    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->getFirstMediaUrl(),
            'brand'=>$this->brand->name,
            'category'=>$this->category->name,
            'price' => $this->price->format(),


        ];
    }
}
