<?php

namespace Tasawk\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;
use Tasawk\Models\Catalog\Product;

class IsValidProductOptionValuesRule implements ValidationRule {
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {

        $option = Str::between($attribute, "options.", ".");
        $index = Str::betweenFirst($attribute, ".", ".");

        $productID = request()->input(Str::replace("*", $index, 'products.*.id'));

        $optionID = request()->input(Str::replace("*", $index, "products.$index.options.$option.id"));

        $inventoryProduct = Product::find($productID)->branchInventory();
        $option = $inventoryProduct->options()->where('id', $optionID)->first();

        if ($notExists = !$option?->values()?->where("id", $value)->exists()) {
            $fail(__("validation.api.product.value_not_exists", ['title' => $optionID]));
            return;
        }
        if (!$option->values()->where("id", $value)->first()?->isAvailable()) {
            $fail(__("validation.api.product.value_not_available", ['title' => $option->values()->where("id", $value)->first()->value->value->value]));

        }
    }
}
