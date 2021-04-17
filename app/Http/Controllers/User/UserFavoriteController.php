<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserFavoriteController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function index(User $user): JsonResponse
    {
        $articles = $user->favorites;
        return $this->showAll($articles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function store(Request $request, User $user): JsonResponse
    {
        $article = Article::query()->findOrFail($request->id);
        if ($user->favorites->contains($article))
        {
            return $this->errorResponse(
                'Este articulo ya se encuentra en los favoritos!'
            );
        } else
        {
            $user->favorites()->attach($article);
            return $this->api_success([
                'data' => $article,
                'message' => 'articulo agregago a favoritos con exito!',
                'code' => 200
            ]);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @param $id
     * @return JsonResponse
     */
    public function destroy(User $user, $id)
    {
        $article = Article::query()->findOrFail($id);

        if ($user->favorites->contains($article))
        {
            $user->favorites()->detach($article);
            return $this->api_success([
                'data' => $article,
                'message' => 'articulo eliminado de favoritos!',
                'code' => 200
            ]);
        } else
        {
            return $this->errorResponse(
                'Este articulo no se encuentra en los favoritos!'
            );
        }
    }
}
