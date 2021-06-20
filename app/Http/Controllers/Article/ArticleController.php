<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Resource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ArticleController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {

        return $this->collectionResponse(ArticleResource::collection($this->getModel(new Article, [],Article::with('keywords','resources')->select(
'id',
'article_id',
'date',
'title',
'extract',
'state',
'type',
'visibility',
'total_score',
'primary_image'
        ))));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreArticleRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        $article = new Article;
        $article->fill($request->all());
        $article->saveOrFail();

        if ($request->has('keywords')) {
            $article->keywords()->sync($request->keywords);
        }

        if ($request->has('resources')) {
            foreach ($request->resources as $resource) {
                if ($resource['details'] === 'imagen') {
                    $resourceArticle = new Resource;
                    $image_64 = $resource['url'];
                    $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
                    $image = str_replace('data:image/jpeg;base64,', '', $image_64);
                    $image = str_replace(' ', '+', $image);
                    $imageName = Str::random(10).'.'.$extension;
                    Storage::disk('article')->put($imageName, base64_decode($image));
                    $resourceArticle->details = $resource['details'];
                    $resourceArticle->url = $imageName;
                    $resourceArticle->article_id = $article->id;
                    $resourceArticle->save();
                } elseif ($resource['details'] === 'video') {
                    $resourceArticle = new Resource;
                    $image_64 = $resource['url'];
                    $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
                    $image = str_replace('data:video/mp4;base64,', '', $image_64);
                    $image = str_replace(' ', '+', $image);
                    $imageName = Str::random(10).'.'.$extension;
                    Storage::disk('article')->put($imageName, base64_decode($image));
                    $resourceArticle->details = $resource['details'];
                    $resourceArticle->url = $imageName;
                    $resourceArticle->article_id = $article->id;
                    $resourceArticle->save();
                }

            }
        }

        return $this->api_success([
            'data' => 'created',
            'message' => __('pages.responses.created'),
            'code' => 201
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function show(Article $article): JsonResponse
    {
        $article->load(['keywords','resources', 'scores','childrenArticles'=>function($query){
		
		$query->select('id',
'article_id',
'date',
'title',
'extract',
'state',
'type',
'visibility',
'total_score',
'primary_image');
	
	}]);
        return $this->singleResponse(new ArticleResource($article));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateArticleRequest $request
     * @param Article $article
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(UpdateArticleRequest $request, Article $article): JsonResponse
    {
        if ($request->has('date')) {
            $article->date = $request->date;
        }

        if ($request->has('title')) {
            $article->title = $request->title;
        }

        if ($request->has('extract')) {
            $article->extract = $request->extract;
        }

       if ($request->has('author')) {
            $article->author = $request->author;
        }


        if ($request->has('content')) {
            $article->content = $request['content'];
        }

        if ($request->has('state')) {
            $article->state = $request->state;
        }

        if ($request->has('type')) {
            $article->type = $request->type;
        }

        if ($request->has('visibility')) {
            $article->visibility = $request->visibility;
        }

        if ($request->has('article_id')) {
            $article->article_id = $request->article_id;
        }

        if ($request->has('keywords')) {
            $article->keywords()->sync($request->keywords);
        }

        if ($article->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $article->saveOrFail();
        return $this->api_success([
            'data'      =>  new ArticleResource($article),
            'message'   => __('pages.responses.updated'),
            'code'      =>  200
        ]);
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
        return $this->api_success([
            'data' => new ArticleResource($article),
            'message' => __('pages.responses.deleted'),
            'code' => 200
        ]);
    }
    
     /**
     * List articles.
     *
     * @return JsonResponse
     * @throws Exception
     */

    public function list_public ($type){

        $articles = Article::select('id','extract','article_id','title','type','primary_image')->with('keywords')
	->where('type',$type)
	->where('state',0)->get();
        return $this->api_success([
            'data' => $articles,
            'message' => 'lista de articulos',
            'code' => 200
        ]);

    }

    /**
     * List type articles.
     *
     * @return JsonResponse
     * @throws Exception
     */

    public function list_type (UpdateArticleRequest $request, $type){

        $articles = Article::select('id','article_id','extract','title','type','primary_image')->with('keywords')->where('type',$type)->get();
        return $this->api_success([
            'data' => $articles,
            'message' => 'lista de tipos de articulos',
            'code' => 200
        ]);

    }
/**
     * Display the specified resource public.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function show_public($id)
    {  
 	$article = Article::with(['keywords','resources', 'scores','childrenArticles'=>function($query){

                $query->select('id',
'article_id',
'date',
'title',
'extract',
'state',
'type',
'visibility',
'total_score',
'primary_image');
	
        }])->find($id);
        return $this->api_success([
            'data' => $article,
            'message' => 'articulo consultado correctamente',
            'code' => 200
        ]);
        
    }
}
