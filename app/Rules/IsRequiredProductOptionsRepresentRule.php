<?php

namespace Tasawk\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;
use Tasawk\Models\Catalog\Product;

class IsRequiredProductOptionsRepresentRule implements ValidationRule {
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        $index = Str::betweenFirst($attribute, ".", ".");
        $inventoryProduct = Product::find($value)?->branchInventory();

        if (!$inventoryProduct || !$inventoryProduct?->exists()) {
            $fail(__('validation.exists',['attribute' => __("validation.attributes.id")]));
            return;
        }
        $enteredOptions = request()->collect(Str::replace("*", $index, 'products.*.options'))->pluck('id')->toArray();

        $productOptions = $inventoryProduct->options()
            ->whereOriginOptionEnabled()
            ->whereProductOptionRequired()
            ->whereProductOptionStatusEnabled()
            ->whereNotIn('id', $enteredOptions)
            ->get();
        foreach ($productOptions as $option) {
            $fail(__('validation.api.product.option_required', ['title' => $option->option->option->name]));

        }


    }
}
