<?php

namespace Tasawk\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Tasawk\Api\V1\Customer\CreateOrder\Design\Mappers\GardensMapper;
use Tasawk\Api\V1\Customer\CreateOrder\Design\Mappers\LivingRoomMapper;
use Tasawk\Api\V1\Customer\CreateOrder\Design\Mappers\MapperItem;
use Tasawk\Api\V1\Customer\CreateOrder\Design\Mappers\OfficeMapper;
use Tasawk\Models\Catalog\Product;

class IsValidGardensRoomItemsRule implements ValidationRule {

    public function validate(string $attribute, mixed $value, Closure $fail): void {
        foreach ($value as $option) {
            $id = $option['id'];
            /**
             * @var MapperItem $mapper_item
             * */
            $mapper_item = GardensMapper::getMapper()->$id();
            if ($option['count'] !== count($option['values'])) {
                $fail(__("validation.size.numeric", ['attribute' => $id, 'size' => $option['count']]));
            }
            if ($mapper_item->min() > $option['count']) {
                $fail(__("validation.min.numeric", ['attribute' => $id, 'min' => $mapper_item->min()]));
            }
            if ($mapper_item->max() < $option['count']) {
                $fail(__("validation.max.numeric", ['attribute' => $id, 'min' => $mapper_item->max()]));
            }
            if ($mapper_item->groupHasType() && !isset($option['type'])) {
                $fail(__("The product :id Must have type", ['id' => $option['id']]));
                return;
            }
            collect($option['values'])
                ->each(function ($value) use ($fail, $mapper_item, $id, $option) {

                    if (!$mapper_item->productsIds()->contains($value['id'])) {
                        $fail(__("There is no product with :id category :optionIndex", ['id' => $value['id'], 'optionIndex' => $id]));
                    }


                });


        }
    }
}
