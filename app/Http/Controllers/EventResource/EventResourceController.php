<?php

namespace App\Http\Controllers\EventResource;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreEventResourceRequest;
use App\Http\Resources\EventResourceResource;
use App\Models\EventResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class EventResourceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->collectionResponse(EventResourceResource::collection($this->getModel(new EventResource, [])));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEventResourceRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(StoreEventResourceRequest $request): JsonResponse
    {
        $eventResource = new EventResource;
        $eventResource->fill($request->all());

        if ($request->hasFile('url')) {
            $eventResource->url = $request->url->store('/', 'event');
        }
        $eventResource->saveOrFail();
        return $this->api_success([
            'data' => new EventResourceResource($eventResource),
            'message' => __('pages.responses.created'),
            'code' => 201
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param EventResource $eventResource
     * @return JsonResponse
     */
    public function show(EventResource $eventResource): JsonResponse
    {
        return $this->singleResponse(new EventResourceResource($eventResource));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param EventResource $eventResource
     * @return JsonResponse
     */
    public function update(Request $request, EventResource $eventResource): JsonResponse
    {
        if ($request->has('type')) {
            $eventResource->type = $request->type;
        }

        if ($request->hasFile('url')) {
            Storage::disk('event')->delete($eventResource->url);
            $eventResource->url = $request->url->store('/', 'event');
        }

        if (!$eventResource->isDirty()) {
            return $this->errorResponse(
                'Se debe especificar al menos un valor diferente para actualizar',
            );
        }
        $eventResource->saveOrFail();
        return $this->api_success([
            'data' => new EventResourceResource($eventResource),
            'message' =>  __('pages.responses.updated'),
            'code' => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param EventResource $eventResource
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(EventResource $eventResource): JsonResponse
    {
        Storage::disk('event')->delete($eventResource->url);
        $eventResource->delete();
        return $this->singleResponse(new EventResourceResource($eventResource));
    }
}
