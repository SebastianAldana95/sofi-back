<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
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
        /*$events = $user->events()->with(['resources', 'notifications' => function($query) {
          return $query->orderBy('resources.order');
        }]);*/
        $events = $user->events;
        return $this->showAll($events);
    }
}
