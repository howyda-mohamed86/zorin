<?php

namespace Tasawk\Lib;

use Darryldecode\Cart\CartCollection;
use Darryldecode\Cart\CartConditionCollection;
use Darryldecode\Cart\ItemCollection;
use Tasawk\Currencies\Models\Currency;
use Tasawk\Ecommerce\Models\Cart;

class ArrayStorage {

    private array $cart = [];

    public function has($key) {
        return isset($this->cart[$key]);
    }

    public function get($key): CartCollection|array {
        if ($this->has($key)) {
            return new CartCollection($this->cart[$key]);
        } else {
            return [];
        }
    }

    public function put($key, $value) {
        $this->cart[$key] = $value;
    }
}

