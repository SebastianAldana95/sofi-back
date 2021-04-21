<?php

namespace App\Http\Controllers\Keyword;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
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
        $keyword->articles;
        return $this->showOne($keyword);
    }

}
