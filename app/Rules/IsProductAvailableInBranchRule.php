<?php

namespace Tasawk\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Tasawk\Models\Catalog\Product;

class IsProductAvailableInBranchRule implements ValidationRule {
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {

        $inventoryProduct = Product::find($value)?->branchInventory();

        if (!$inventoryProduct || !$inventoryProduct?->exists()) {
            $fail(__('validation.exists',['attribute' => __("validation.attributes.id")]));
            return;
        }
        if (!$inventoryProduct?->product?->status) {
            $fail(__('validation.api.product.not_available', ['title' => $inventoryProduct->product->title]));
            return;
        }
        if (!$inventoryProduct?->status) {
            $fail(__('validation.api.product.not_available', ['title' => $inventoryProduct->product->title]));
            return;
        }
        if (!$inventoryProduct->isAvailable()) {
            $fail(__('validation.api.product.time_up', ['title' => $inventoryProduct->product->title, 'from' => $inventoryProduct->from, 'to' => $inventoryProduct->to]));
            return;
        }
    }
}
