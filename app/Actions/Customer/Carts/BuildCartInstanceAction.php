<?php

namespace Tasawk\Actions\Customer\Carts;

use Lorisleiva\Actions\Concerns\AsAction;
use Tasawk\Lib\Cart;
use Tasawk\Models\Catalog\Product;


class BuildCartInstanceAction {
    use AsAction;

    protected $data = [];
    public $products = [];
    public $cart = null;

    public function handle() {
        /**
         *
         * @var Cart $cart
         * */
        $this->cart = app('cart');
        $this->products = Product::findMany(request()->collect('items')->pluck('values.*.id')->collapse());
        foreach (request()->collect('items') as $group) {
            if (!isset($group['type']) || $group['type'] == 'brand') {
                $this->addBrandItemToCart($group);
            } else {
                $this->addCustomItemToCart($group);
            }
        }
        $this->cart->applyTaxes();


        return $this->cart;

    }

    public function addBrandItemToCart($group): bool {

        foreach ($group['values'] as $_product) {
            $product = $this->products->where('id', $_product['id'])->first();

            $this->cart->applyItem($product, 1,
                [
                    ...collect($_product)->except(['values', 'id'])->toArray(),
                    ...collect($product)->except(['id'])->toArray(),
                    'group' => $group['id'],
                    'group_type' => $group['type'] ?? 'brand',
                ],
            );
        }

        return true;
    }

    public function addCustomItemToCart($group): bool {
        foreach ($group['values'] as $_product) {
            $product = Product::where("id", $_product['value_id'])->first();
            $this->cart->applyItem($product, 1,
                [
                    ...collect($_product)->except(['values', 'id'])->toArray(),
                    ...collect($product)->except(['id'])->toArray(),
                    'group' => $group['id'],
                    'group_type' => $group['type'],

                ],
            );
        }
        return true;
    }


}
