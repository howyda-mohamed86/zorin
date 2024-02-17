<?php

namespace Tasawk\Http\Resources\Api\Categories;

use Illuminate\Http\Resources\Json\JsonResource;
use Tasawk\Http\Resources\Api\Categories\IndividualServicesResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' =>  $this->status == 1 ?  __('forms.fields.active') : __('forms.fields.inactive'),
            'individualServices' => IndividualServicesResource::collection($this->whenLoaded('individualServices')),
        ];
    }
}
