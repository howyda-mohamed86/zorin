<?php

namespace Tasawk\Api\V1\Shared;

use Tasawk\Api\Core;
use Tasawk\Api\Facade\Api;
use Tasawk\Http\Resources\Api\Shared\CategoryResource;
use Tasawk\Http\Resources\Api\Shared\DesignResource;
use Tasawk\Http\Resources\Api\Shared\PatternResource;
use Tasawk\Lib\Utils;
use Tasawk\Models\Catalog\Category;
use Tasawk\Models\Design\Pattern;

class CategoryServices {
    public function list() {
        $type = request()->get('type', 'design');
        $categories = Category::parent()
            ->$type()
            ->enabled()
            ->latest()
            ->get();
        return Api::isOk(__("List of categories"), CategoryResource::collection($categories));
    }

    public function show(Category $category) {
        return Api::isOk(__("Category information"), new CategoryResource($category));
    }

    public function patterns(Category $category): Core {
        $patterns = $category
            ->patterns()
            ->enabled()
            ->latest()
            ->get();
        return Api::isOk(__("List of patterns"), PatternResource::collection($patterns));

    }

    public function designs(Category $category, Pattern $pattern): Core {
        $patterns = $pattern
            ->designs()
            ->enabled()
            ->latest()
            ->get();
        return Api::isOk(__("List of designs"), DesignResource::collection($patterns));

    }

    public function products() {

    }

}
