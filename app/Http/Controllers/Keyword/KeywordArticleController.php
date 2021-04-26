<?php

namespace App\Http\Controllers\Keyword;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Keyword;
use Illuminate\Http\JsonResponse;

class KeywordArticleController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Keyword $keyword
     * @return JsonResponse
     */
    public function index(Keyword $keyword): JsonResponse
    {
        $articles = $keyword->articles();
        return $this->collectionResponse(ArticleResource::collection($this->getModel(new Article, [], $articles)));
    }

}
