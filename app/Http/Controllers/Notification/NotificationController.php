<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class NotificationController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->collectionResponse(NotificationResource::collection($this->getModel(new Notification, [])));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNotificationRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(StoreNotificationRequest $request): JsonResponse
    {
        $notification = new Notification;
        $notification->fill($request->all());
        $notification->saveOrFail();
        return $this->api_success([
            'data' => new NotificationResource($notification),
            'message' => __('pages.responses.created'),
            'code' => 201
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Notification $notification
     * @return JsonResponse
     */
    public function show(Notification $notification): JsonResponse
    {
        return $this->singleResponse(new NotificationResource($notification));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateNotificationRequest $request
     * @param Notification $notification
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(UpdateNotificationRequest $request, Notification $notification): JsonResponse
    {

        if ($request->has('date')) {
            $notification->date = $request->date;
        }

        if ($request->has('details')) {
            $notification->details = $request->details;
        }

        if (!$notification->isDirty()) {
            return $this->errorResponse(
                'Se debe especificar al menos un valor diferente para actualizar',
            );
        }
        $notification->saveOrFail();
        return $this->api_success([
            'data'      =>  new NotificationResource($notification),
            'message'   => __('pages.responses.updated'),
            'code'      =>  200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Notification $notification
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Notification $notification): JsonResponse
    {
        $notification->delete();
        return $this->api_success([
            'data' => new NotificationResource($notification),
            'message' => __('pages.responses.deleted'),
            'code' => 200
        ]);
    }
}
