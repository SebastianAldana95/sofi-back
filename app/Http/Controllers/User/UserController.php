<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;


class UserController extends ApiController
{

    public function __construct()
    {
        parent::__construct();

        // $this->middleware('role:user')->except(['edit', 'create']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->collectionResponse(UserResource::collection($this->getModel(new User, ['roles'])));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = new User;
        $user->fill($request->all());
        $user->user = 'manual';
        $user->state = 'pendiente';
        $user->syncRoles('User');
        $user->saveOrFail();

        // Mail de bienvenida!

        return $this->api_success([
            'data' => new UserResource($user),
            'message' => __('pages.responses.created'),
            'code' => 201
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        $user->roles;
        return $this->singleResponse(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        if ($user->user === 'ldap') {
            if ($request->has('photo')) {
                Storage::disk('photo')->delete($user->photo);
                $user->photo = $request->photo->store('photos', 'photo');
            }

            if ($request->has('state')) {
                $user->state = $request->state;
            }
        } elseif ($user->user === 'manual') {
            if ($request->has('identification')) {
                $user->identification = $request->identification;
            }

            if ($request->has('username')) {
                $user->username = $request->username;
            }

            if ($request->has('name')) {
                $user->name = $request->name;
            }

            if ($request->has('lastname')) {
                $user->lastname = $request->lastname;
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }

            if ($request->has('title')) {
                $user->title = $request->title;
            }

            if ($request->has('institution')) {
                $user->institution = $request->institution;
            }

            if ($request->has('phone1')) {
                $user->phone1 = $request->phone1;
            }

            if ($request->has('phone2')) {
                $user->phone2 = $request->phone2;
            }

            if ($request->has('address')) {
                $user->address = $request->address;
            }

            if ($request->has('alternatename')) {
                $user->alternatename = $request->alternatename;
            }

            if ($request->has('url')) {
                $user->url = $request->url;
            }

            if ($request->has('lang')) {
                $user->lang = $request->lang;
            }

            if ($request->has('firstnamephonetic')) {
                $user->firstnamephonetic = $request->firstnamephonetic;
            }

            if ($request->has('lastnamephonetic')) {
                $user->lastnamephonetic = $request->lastnamephonetic;
            }

            if ($request->has('middlename')) {
                $user->middlename = $request->middlename;
            }

            if ($request->has('photo')) {
                Storage::disk('photo')->delete($user->photo);
                $user->photo = $request->photo->store('photos', 'photo');
            }

            if ($request->has('city')) {
                $user->city = $request->city;
            }

            if ($request->has('country')) {
                $user->country = $request->country;
            }

            if ($request->has('state')) {
                $user->state = $request->state;
            }
        }

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        /*if (!$user->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 409);
        }*/

        $user->save();

        return $this->api_success([
            'data'      =>  new UserResource($user),
            'message'   => __('pages.responses.updated'),
            'code'      =>  201
        ], 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(User $user): JsonResponse
    {
        Storage::disk('photo')->delete($user->photo);
        $user->delete();
        return $this->api_success([
            'data'      =>  new UserResource($user),
            'message'   => __('pages.responses.deleted'),
            'code'      =>  200
        ]);
    }

    /**
     * Enalbe user in the table users.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function enable(User $user): JsonResponse
    {
        $user->state = 'activo';
        $user->save();
        return $this->singleResponse(new UserResource($user));
    }

    /**
     * Disable user in the table users.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function disable(User $user): JsonResponse
    {
        $user->state = 'inactivo';
        $user->save();
        return $this->singleResponse(new UserResource($user));
    }
}
