<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventResource;
use App\Http\Resources\EventResource as EventResources;
use App\Models\Notification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EventController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:events.index')->only('index');
        $this->middleware('permission:events.store')->only('store');
        $this->middleware('permission:events.show')->only('show');
        $this->middleware('permission:events.update')->only('update');
        $this->middleware('permission:events.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->collectionResponse(EventResources::collection($this->getModel(new Event, ['resources', 'notifications'])));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = new Event;
        $event->fill($request->all());
        $event->saveOrFail();
        $event->users()->attach($request->user_id);

        if ($request->has('notifications')) {
            foreach ($request->notifications as $notification) {
                $notification = new Notification([
                    'date' => Carbon::now(),
                    'details' => $notification['details'],
                    'event_id' => $event->id,
                ]);
                $notification->save();
            }
        }

        if ($request->has('resources')) {
            foreach ($request->resources as $resource) {
                $resourceEvent = new EventResource;
                $resourceEvent->type = $resource['type'];
                $image_64 = $resource['url'];
                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];

                $replace = substr($image_64, 0, strpos($image_64, ',')+1);
                // find substring fro replace here eg: data:image/png;base64,
                $image = str_replace($replace, '', $image_64);
                $image = str_replace(' ', '+', $image);
                $imageName = Str::random(10).'.'.$extension;
                Storage::disk('event')->put($imageName, base64_decode($image));
                $resourceEvent->url = $imageName;
                $resourceEvent->event_id = $event->id;
                $resourceEvent->save();
            }
        }

        return $this->api_success([
            'data' => new EventResources($event->load(['resources', 'notifications'])),
            'message' => __('pages.responses.created'),
            'code' => 201
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param Event $event
     * @return JsonResponse
     */
    public function show(Event $event): JsonResponse
    {
        $event->load(['resources','notifications']);
        return $this->singleResponse(new EventResources($event));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Event $event
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateEventRequest $request, Event $event): JsonResponse
    {
        if ($request->has('name')) {
            $event->name = $request->name;
        }

        if ($request->has('start_date')) {
            $event->start_date = $request->start_date;
        }

        if ($request->has('end_date')) {
            $event->end_date = $request->end_date;
        }

        if ($request->has('state')) {
            $event->state = $request->state;
        }

        if ($request->has('information')) {
            $event->information = $request->information;
        }

        if ($request->has('place')) {
            $event->place = $request->place;
        }

        if ($request->has('visibility')) {
            $event->visibility = $request->visibility;
        }

        if (!$event->isDirty()) {
            return $this->errorResponse(
                'Se debe especificar al menos un valor diferente para actualizar',
            );
        }
        $event->saveOrFail();
        return $this->api_success([
            'data'      =>  new EventResources($event),
            'message'   => __('pages.responses.updated'),
            'code'      =>  200
        ]);
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
        return $this->api_success([
            'data' => new EventResources($event),
            'message' => __('pages.responses.deleted'),
            'code' => 200
        ]);
    }
}
