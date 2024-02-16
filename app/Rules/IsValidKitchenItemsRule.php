<?php

namespace Tasawk\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Tasawk\Api\V1\Customer\CreateOrder\Design\Mappers\KitchenMapper;

class IsValidKitchenItemsRule implements ValidationRule {

    public function validate(string $attribute, mixed $value, Closure $fail): void {

        foreach ($value as $option) {
            $id = $option['id'];

            $mapper_item = KitchenMapper::getMapper()->$id();
            if ($mapper_item->min() > $option['count']) {
                $fail(__("validation.min.numeric", ['attribute' => $id, 'min' => $mapper_item->min()]));
            }
            if ($mapper_item->max() < $option['count']) {
                $fail(__("validation.max.numeric", ['attribute' => $id, 'max' => $mapper_item->max()]));
            }
            if ($option['count'] !== count($option['values'])) {
                $fail(__("validation.size.numeric", ['attribute' => $id, 'size' => $option['count']]));
            }

//            if ($mapper_item->hasHeight() && !isset($option['dimensions']['height'])) {
//                $fail(__("Height is required for :attribute", ['attribute' => $id]));
//            }
//            if ($mapper_item->hasWidth() && !isset($option['dimensions']['width'])) {
//                $fail(__("Width is required for :attribute", ['attribute' => $id]));
//            }
//            if ($mapper_item->hasLength() && !isset($option['dimensions']['length'])) {
//                $fail(__("Length is required for :attribute", ['attribute' => $id]));
//            }
            collect($option['values'])->each(function ($value) use ($fail, $mapper_item, $id) {

                $product = $mapper_item
                    ->productsIds()
                    ->contains($value['id']);

                if (!$product) {
                    $fail(__("There is no product with :id category :optionIndex", ['id' => $value['id'], 'optionIndex' => $id]));
                }
            });

            if ($mapper_item->productMustBeIdenticalInCategory() && !$mapper_item->isProductsHasTheSameCategory($option['values'])) {

                $fail(__('All products must be at the same category for :attribute', ['attribute' => $id]));

            }


        }
    }
}
