<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\ApiController;
use App\Models\Article;
use App\Models\Resource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ResourceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $resources = Resource::all();
        return $this->showAll($resources);
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
            'details' => 'required',
            'url' => 'required|file',
            'article_id' => 'required|integer',
        ];

        $this->validate($request, $validations);
        $resource = $request->all();
        $resource['url'] = $request->url->store('articles', 'article');

        $resource = Resource::query()->create($resource);
        return $this->showOne($resource, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Resource $resource
     * @return JsonResponse
     */
    public function show(Resource $resource): JsonResponse
    {
        return $this->showOne($resource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Resource $resource
     * @return JsonResponse
     */
    public function update(Request $request, Resource $resource): JsonResponse
    {
        $resource->fill($request->only([
            'details',
            'url',
            'article_id',
        ]));

        if ($request->hasFile('url')) {
            Storage::disk('article')->delete($resource->url);
            $resource->url = $request->url->store('articles', 'article');
        }

        if ($resource->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $resource->save();
        return $this->showOne($resource);
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
        return $this->showOne($resource);
    }
}
