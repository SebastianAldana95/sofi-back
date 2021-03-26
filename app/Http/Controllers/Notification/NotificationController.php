<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\ApiController;
use App\Models\Notification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NotificationController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $notifications = Notification::all();
        return $this->showAll($notifications);
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
            'date' => 'required|date',
            'details' => 'required|string',
            'event_id' => 'required|integer',
        ];

        $this->validate($request, $validations);
        $notification = Notification::query()->create($request->all());
        return $this->showOne($notification, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Notification $notification
     * @return JsonResponse
     */
    public function show(Notification $notification): JsonResponse
    {
        return $this->showOne($notification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Notification $notification
     * @return JsonResponse
     */
    public function update(Request $request, Notification $notification): JsonResponse
    {
        $notification->fill($request->only([
            'date',
            'details',
            'event_id',
        ]));

        if ($notification->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar');
        }
        $notification->save();
        return $this->showOne($notification);
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
        return $this->showOne($notification);
    }
}
