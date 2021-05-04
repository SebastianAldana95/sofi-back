<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ResourceArticleResource;
use App\Models\Article;
use App\Models\Resource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class ResourceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->collectionResponse(ResourceArticleResource::collection($this->getModel(new Resource, ['articles'])));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreResourceRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(StoreResourceRequest $request): JsonResponse
    {
        $resource = new Resource;
        $resource->fill($request->all());
        if ($request->has('url')) {
            $resource->url = $request->file('url')->store('articles', 'article');
        }
        $resource->saveOrFail();
        return $this->api_success([
            'data' => new ResourceArticleResource($resource),
            'message' => __('pages.responses.created'),
            'code' => 201
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Resource $resource
     * @return JsonResponse
     */
    public function show(Resource $resource): JsonResponse
    {
        return $this->singleResponse(new ResourceArticleResource($resource));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Resource $resource
     * @return JsonResponse
     */
    public function update(UpdateResourceArticleRequest $request, Resource $resource): JsonResponse
    {

        if ($request->hasFile('url')) {
            Storage::disk('article')->delete($resource->url);
            $resource->url = $request->url->store('articles', 'article');
        }

        if ($resource->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $resource->save();
        return $this->api_success([
            'data'      =>  new ResourceArticleResource($resource),
            'message'   => __('pages.responses.updated'),
            'code'      =>  200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Resource $resource
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Resource $resource): JsonResponse
    {
        Storage::disk('article')->delete($resource->url);
        $resource->delete();
        return $this->api_success([
            'data' => new ResourceArticleResource($resource),
            'message' => __('pages.responses.deleted'),
            'code' => 200
        ]);
    }
}
