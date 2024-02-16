<?php

namespace Tasawk\Api\V1\Customer;

use Tasawk\Api\Core;
use Tasawk\Api\Facade\Api;
use Tasawk\Http\Resources\Api\Customer\Products\LightProductResource;
use Tasawk\Http\Resources\Api\Customer\Products\ProductResource;
use Tasawk\Models\Catalog\Product;

class ProductServices  {
    //Todo:Fetch Products in current selected branch
    public function search(): Core {
        return Api::isOk("Search Results",
            LightProductResource::collection(
                Product::whereBelongToPresentedBranchHeader()->enabled()->latest()->paginate()
            )
        );
    }

    public function index() {
        return Api::isOk("Products List",
            LightProductResource::collection(
                Product::whereBelongToPresentedBranchHeader()->enabled()->latest()->paginate()
            )
        );
    }

    public function show(Product $product) {
        return Api::isOk(__("Product information"),  ProductResource::make($product));
    }

    public function toggleFavorite(Product $product): Core {
        $product->toggleFavorite();
        return Api::isOk(__("Favorites list updated"));

    }


}
