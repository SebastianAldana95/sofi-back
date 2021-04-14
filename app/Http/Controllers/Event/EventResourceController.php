<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\EventResourceResource;
use App\Models\Event;
use App\Models\EventResource;
use Illuminate\Http\JsonResponse;

class EventResourceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Event $event
     * @return JsonResponse
     */
    public function index(Event $event): JsonResponse
    {
        $resources = $event->resources();
        return $this->collectionResponse(EventResourceResource::collection($this->getModel(new EventResource, [], $resources)));
    }

}
