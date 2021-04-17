<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResourceResource;
use App\Models\Event;
use App\Models\User;
use Facade\FlareClient\Api;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserEventController extends ApiController
{
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

        return $this->collectionResponse(EventResourceResource::collection($this->getModel(new Event, ['resources', 'notifications'], $events)));
    }
}
