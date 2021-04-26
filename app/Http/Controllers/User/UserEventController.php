<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserEventController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('permission:users.events.index')->only('index');

    }
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function index(User $user): JsonResponse
    {
        $events = $user->events()->with(['resources', 'notifications'])
            ->where('visibility', '=', 1)
            ->latest();

        return $this->collectionResponse(EventResource::collection($this->getModel(new Event, ['resources', 'notifications'], $events)));
    }
}
