<?php

namespace App\Http\Controllers\Keyword;

use App\Http\Controllers\Api\ApiController;
use App\Models\Keyword;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class KeywordController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $keywords = Keyword::all();
        return $this->showAll($keywords);
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
            'name' => 'required|string',
        ];
        $this->validate($request, $validations);
        $keyword = Keyword::query()->create($request->all());
        return $this->showOne($keyword, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param Keyword $keyword
     * @return JsonResponse
     */
    public function show(Keyword $keyword): JsonResponse
    {
        return $this->showOne($keyword);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Keyword $keyword
     * @return JsonResponse
     */
    public function update(Request $request, Keyword $keyword): JsonResponse
    {
        $keyword->fill($request->only([
            'name',
        ]));

        if ($keyword->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar');
        }
        $keyword->save();
        return $this->showOne($keyword);
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
        return $this->showOne($keyword);
    }
}
