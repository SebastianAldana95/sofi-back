<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\NotificationResource;
use App\Models\Event;
use App\Models\Notification;
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
        $notifications = $event->notifications();
        return $this->collectionResponse(NotificationResource::collection($this->getModel(new Notification, [], $notifications)));
    }

}
