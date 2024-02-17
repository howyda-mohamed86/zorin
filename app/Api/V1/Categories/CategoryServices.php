<?php

namespace Tasawk\Api\V1\Categories;

use Tasawk\Api\Facade\Api;
use Tasawk\Models\Hotel;
use Tasawk\Http\Resources\Api\Categories\CategoryResource;
use Tasawk\Models\Catalog\Category;
use Tasawk\Models\IndividualService;
use Tasawk\Http\Resources\Api\Categories\IndividualServicesResource;

class CategoryServices
{


    public function list()
    {
        $categories = Category::where('status', 1)->with('individualServices')->get();
        return Api::isOk(__("Categories list"), CategoryResource::collection($categories));
    }

    public function show(IndividualService $individualService)
    {
        $individualService = IndividualService::where('status', 1)
            ->where('id', $individualService->id)
            ->first();
        return Api::isOk(__("Individual service details"), IndividualServicesResource::make($individualService));
    }

}
