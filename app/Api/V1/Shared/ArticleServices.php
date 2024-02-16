<?php

namespace Tasawk\Api\V1\Shared;

use Tasawk\Api\Facade\Api;
use Tasawk\Http\Resources\Api\Shared\PostResource;
use Tasawk\Models\Content\Post;

class ArticleServices {
    public function list() {
        $articles = Post::enabled()->latest()->get();
        return Api::isOk(__("Posts list"),PostResource::collection($articles));
    }

    public function show(Post $article) {

        return Api::isOk(__("Posts list"),PostResource::make($article));
    }


}
