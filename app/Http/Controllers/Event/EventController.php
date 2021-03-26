<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\ApiController;
use App\Models\Event;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EventController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $events = Event::with('resources')
            ->latest('start_date')
            ->get();
        return $this->showAll($events);
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
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'information' => 'required|string',
            'place' => 'required|string'
        ];

        $this->validate($request, $validations);
        $event = Event::query()->create($request->all());
        return $this->showOne($event, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param Event $event
     * @return JsonResponse
     */
    public function show(Event $event): JsonResponse
    {
        $event->resources;
        return $this->showOne($event);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Event $event
     * @return JsonResponse
     */
    public function update(Request $request, Event $event): JsonResponse
    {
        $event->fill($request->only([
            'name',
            'start_date',
            'end_date',
            'state',
            'information',
            'place',
            'visibility',
        ]));

        if ($event->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }
        $event->save();
        return $this->showOne($event);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Event $event
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Event $event): JsonResponse
    {
        $event->delete();
        return $this->showOne($event);
    }
}
