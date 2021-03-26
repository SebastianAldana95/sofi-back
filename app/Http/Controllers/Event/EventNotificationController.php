<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\ApiController;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

class EventNotificationController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Event $event
     * @return JsonResponse
     */
    public function index(Event $event): JsonResponse
    {
        $event->notifications;
        return $this->showOne($event);
    }

}
