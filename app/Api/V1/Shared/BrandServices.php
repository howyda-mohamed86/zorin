<?php

namespace Tasawk\Api\V1\Shared;

use Tasawk\Api\Core;
use Tasawk\Api\Facade\Api;
use Tasawk\Http\Resources\Api\Customer\Products\LightProductResource;
use Tasawk\Http\Resources\Api\Customer\Products\ProductResource;
use Tasawk\Http\Resources\Api\Shared\CategoryResource;
use Tasawk\Http\Resources\Api\Shared\DesignResource;
use Tasawk\Http\Resources\Api\Shared\PatternResource;
use Tasawk\Models\Brand;
use Tasawk\Models\Catalog\Category;
use Tasawk\Models\Catalog\Product;
use Tasawk\Models\Design\Pattern;

class BrandServices {

    public function products(Brand $brand): Core {

        return Api::isOk(__("List of products"), LightProductResource::collection($brand->products()->enabled()->latest()->get()));
    }

    public function product(Brand $brand,Product   $product): Core {

        return Api::isOk(__("Product information"), ProductResource::make($product));
    }

}
