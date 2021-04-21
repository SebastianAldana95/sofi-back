<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Api\ApiController;
use App\Models\Article;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ArticleController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $articles = Article::query()
            ->where('visibility', '=', '1')
            ->latest('date')
            ->with('resources', 'keywords')
            ->get();
        return $this->showAll($articles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validations = [
            'date' => 'required|date',
            'title' => 'required|string',
            'extract' => 'required|string',
            'content' => 'required|string',
            'state' => 'required|string',
            'type' => 'required|string',
            'article_id' => 'integer',
        ];

        $this->validate($request, $validations);
        $article = new Article();
        $article->fill($request->all());
        $article->saveOrFail();

        if ($request->has('keywords')) {
            $article->keywords()->sync($request->keywords);
        }

        $article->keywords;
        return $this->showOne($article, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function show(Article $article): JsonResponse
    {
        $article->resources;
        $article->keywords;
        return $this->showOne($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Article $article
     * @return JsonResponse
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        $article->fill($request->only([
            'date',
            'title',
            'extract',
            'content',
            'state',
            'type',
            'visibility',
            'article_id',
        ]));

        if ($article->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $article->save();
        return $this->showOne($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();
        return $this->showOne($article);
    }
}
