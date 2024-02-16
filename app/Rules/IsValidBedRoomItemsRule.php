<?php

namespace Tasawk\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Tasawk\Api\V1\Customer\CreateOrder\Design\Mappers\BedRoomMapper;
use Tasawk\Models\Catalog\Product;

class IsValidBedRoomItemsRule implements ValidationRule {

    public function validate(string $attribute, mixed $groups, Closure $fail): void {

        foreach ($groups as $option) {
            $id = $option['id'];
            $mapper_item = BedRoomMapper::getMapper()->$id();
            if ($mapper_item->max() < $option['count']) {
                $fail(__("validation.max.numeric", ['attribute' => $id, 'max' => $mapper_item->max()]));
            }
            if ($mapper_item->min() > $option['count']) {
                $fail(__("validation.min.numeric", ['attribute' => $id, 'min' => $mapper_item->min()]));
            }

            if ($option['count'] !== count($option['values'])) {
                $fail(__("validation.size.numeric", ['attribute' => $id, 'size' => $option['count']]));
            }

            if ($mapper_item->groupHasType() && !isset($option['type'])) {
                $fail(__("The product :id Must have type", ['id' => $option['id']]));
                return;
            }
            collect($option['values'])->each(function ($value) use ($fail, $mapper_item, $id, $option) {
                if ($mapper_item->groupHasType() && $option['type'] == 'brand' && !$mapper_item->productsIds()->contains($value['id'])) {
                    $fail(__("There is no product with :id category :optionIndex", ['id' => $value['id'], 'optionIndex' => $id]));
                }

                if ($mapper_item->groupHasType() && $option['type'] == 'custom' && !$mapper_item->designsIds(request()->get('pattern_id'))->contains($value['design_id'])) {
                    $fail(__("invalid design id at :id category", ['id' => $option['id']]));
                }
                if ($mapper_item->groupHasType() && $option['type'] == 'custom' && !isset($value['dimensions'])) {
                    $fail(__("The product :id Must have dimensions", ['id' => $value['id']]));
                }

                if ($mapper_item->groupHasType() && $option['type'] == 'custom' && !isset($value['value_id'])) {
                    $fail(__("missing value_id at group :ID", ['ID' => $option['id']]));
                    return;
                }
                if ($mapper_item->groupHasType() && $option['type'] == 'custom' && !Product::whereHas('category', fn($q) => $q->where('mapper_key', $mapper_item->group()))->where('id', $value['value_id'])->exists()) {
                    $fail(__("There is Invalid custom value ID at group :id ", ['id' => $option['id']]));
                }

            });
        }
    }
}
