<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleParentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $parentArticles = Article::all();
        $parentArticles->parentArticle;
        return $this->showAll($parentArticles);
    }

}
