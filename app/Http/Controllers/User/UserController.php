<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;


class UserController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = User::all();
        return $this->showAll($users);

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
            'identification' => 'required|integer',
            'username' => 'required|string|unique:users',
            'name' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users',
            'title' => 'required|string',
            'institution' => 'required|string',
            'phone1' => 'required|string',
            'phone2' => 'required|string',
            'address' => 'required|string',
            'alternatename' => 'required|string',
            'password' => 'required',
            'photo' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048'

        ];

        $this->validate($request, $validations);
        $userRequest = $request->all();
        $userRequest['password'] = bcrypt($request['password']);
        $userRequest['state'] = 1;
        $userRequest['user'] = 'manual';
        $userRequest['photo'] = $request->photo->store('photos', 'photo');

        $user = User::query()->create($userRequest);
        return $this->showOne($user, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        $user->events;
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validations = [
            'username' => 'string|unique:users',
            'email' => 'email|unique:users',
            'password' => 'min:8|confirmed',
            'photo' => 'image|dimensions:min_width=200,min_height=200',

        ];

        $this->validate($request, $validations);
        if ($user->user === 'ldap') {
            if ($request->has('photo')) {
                Storage::disk('photo')->delete($user->photo);
                $user->photo = $request->photo->store('photos', 'photo');
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

        if (!$user->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 409);
        }

        $user->save();

        return $this->showOne($user);

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
        return $this->showOne($user);
    }

    /**
     * Enalbe user in the table users.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function enable(User $user): JsonResponse
    {
        $user->state = 1;
        $user->save();
        return $this->showOne($user);
    }

    /**
     * Disable user in the table users.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function disable(User $user): JsonResponse
    {
        $user->state = 0;
        $user->save();
        return $this->showOne($user);
    }
}
