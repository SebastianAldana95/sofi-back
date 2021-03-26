<?php

namespace App\Http\Controllers\EventResource;

use App\Http\Controllers\ApiController;
use App\Models\EventResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EventResourceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $resources = EventResource::all();
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
            'type' => 'required',
            'url' => 'required|image|dimensions:min_width=200, min_height=200',
            'event_id' => 'required|integer',
        ];

        $this->validate($request, $validations);
        $eventResourceRequest = $request->all();
        $eventResourceRequest['url'] = $request->url->store('events', 'event');

        $eventResource = EventResource::query()->create($eventResourceRequest);
        return $this->showOne($eventResource, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param EventResource $eventResource
     * @return JsonResponse
     */
    public function show(EventResource $eventResource): JsonResponse
    {
        return $this->showOne($eventResource);
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
        $eventResource->fill($request->only([
            'type',
            'url',
            'event_id',
        ]));

        if ($request->hasFile('url')) {
            Storage::disk('event')->delete($eventResource->url);
            $eventResource->url = $request->url->store('events', 'event');
        }

        if ($eventResource->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $eventResource->save();
        return $this->showOne($eventResource);
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
        return $this->showOne($eventResource);
    }
}
