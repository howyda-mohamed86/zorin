<?php

namespace Tasawk\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Tasawk\Api\V1\Customer\CreateOrder\Design\Mappers\LivingRoomMapper;
use Tasawk\Api\V1\Customer\CreateOrder\Design\Mappers\MapperItem;
use Tasawk\Api\V1\Customer\CreateOrder\Design\Mappers\OfficeMapper;
use Tasawk\Models\Catalog\Product;

class IsValidLivingRoomItemsRule implements ValidationRule {

    public function validate(string $attribute, mixed $value, Closure $fail): void {
        foreach ($value as $option) {
            $id = $option['id'];
            /**
             * @var MapperItem $mapper_item
             * */
            $mapper_item = LivingRoomMapper::getMapper()->$id();
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
            if ($mapper_item->hasOptions()) {
                foreach ($mapper_item->defineOptions() as $defineOption) {
                    collect($option['values'])->each(function ($value) use ($defineOption, $fail, $option) {
                        $optionList = collect($value['options'])->where('id', $defineOption['id'])->first();
                        if (!$optionList) {
                            $fail(__('missing option :ID at group :GROUP', ['ID' => $defineOption['id'], 'GROUP' => $option['id']]));
                            return;
                        }
                        if ($optionList['count'] > $defineOption['range'][0] && $optionList['count'] > $defineOption['range'][1]) {
                            $fail(__('invalid count at option :ID at Group :GROUP', ['ID' => $defineOption['id'], 'GROUP' => $option['id']]));
                            return;
                        }
                        if ($optionList['count'] !== count($optionList['values'])) {
                            $fail(__('invalid values count at option :ID at Group :GROUP', ['ID' => $defineOption['id'], 'GROUP' => $option['id']]));
                            return;
                        }
                        if ($defineOption['group']) {

                            $products = Product::whereHas('category', fn($q) => $q->where('mapper_key', $defineOption['group']))->pluck('id');
                            $ids = collect($optionList['values'])->pluck('id');
                            if (!$products->count()) {
                                $fail(__('There is no products added yet in Dashboard inside :OPTION_ID at Group :GROUP', ["ID" => $id, 'OPTION_ID' => $defineOption['id'], 'GROUP' => $option['id']]));
                                return;
                            }
                            foreach ($ids as $id) {
                                if (!$products->contains($id)) {
                                    $fail(__('invalid value ID :ID at option :OPTION_ID at Group :GROUP', ["ID" => $id, 'OPTION_ID' => $defineOption['id'], 'GROUP' => $option['id']]));
                                    return;
                                }
                            }

                        }
                        if (!$defineOption['group']) {
                            $ids = collect($optionList['values'])->pluck('value', 'id');
                            foreach ($ids as $id => $value) {
                                if (!collect($defineOption['options'])->where("id", $id)->pluck('values')->collapse()->contains($value)) {
                                    $fail(__('invalid value ID :ID at option :OPTION_ID at Group :GROUP', ["ID" => $id, 'OPTION_ID' => $defineOption['id'], 'GROUP' => $option['id']]));
                                    return;
                                }
                            }

                        }

                    });
                }
            }

            if (!in_array($option['id'], ['councils'])) {
                collect($option['values'])
                    ->each(function ($value) use ($fail, $mapper_item, $id, $option) {
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
                        if ($mapper_item->groupHasType() && $option['type'] == 'custom' && !Product::whereHas('category', fn($q) => $q->whereIn('mapper_key', $mapper_item->group()))->where('id', $value['value_id'])->exists()) {
                            $fail(__("There is Invalid custom value ID at group :id ", ['id' => $option['id']]));
                        }

                    });
            }


        }
    }
}
