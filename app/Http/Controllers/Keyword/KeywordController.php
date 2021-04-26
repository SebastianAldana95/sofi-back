<?php

namespace App\Http\Controllers\Keyword;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreKeywordRequest;
use App\Http\Requests\UpdateKeywordRequest;
use App\Http\Resources\KeywordResources;
use App\Models\Keyword;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class KeywordController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->collectionResponse(KeywordResources::collection($this->getModel(new Keyword, ['articles'])));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreKeywordRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(StoreKeywordRequest $request): JsonResponse
    {

        $keyword = new Keyword;
        $keyword->fill($request->all());
        $keyword->saveOrFail();

        return $this->api_success([
            'data' => new KeywordResources($keyword),
            'message' => __('pages.responses.created'),
            'code' => 201,
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param Keyword $keyword
     * @return JsonResponse
     */
    public function show(Keyword $keyword): JsonResponse
    {
        return $this->singleResponse(new KeywordResources($keyword));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateKeywordRequest $request
     * @param Keyword $keyword
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(UpdateKeywordRequest $request, Keyword $keyword): JsonResponse
    {
        if ($request->has('name')) {
            $keyword->name = $request->name;
        }

        if ($keyword->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar');
        }
        $keyword->saveOrFail();
        return $this->api_success([
            'data'      =>  new KeywordResources($keyword),
            'message'   => __('pages.responses.updated'),
            'code'      =>  200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Keyword $keyword
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Keyword $keyword): JsonResponse
    {
        $keyword->delete();
        return $this->api_success([
            'data' => new KeywordResources($keyword),
            'message' => __('pages.responses.deleted'),
            'code' => 200
        ]);
    }
}
