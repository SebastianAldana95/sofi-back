<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\ApiController;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

class EventUserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Event $event
     * @return JsonResponse
     */
    public function index(Event $event): JsonResponse
    {
        $users = $event->users;
        return $this->showAll($users);
    }

}
