<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:roles.index')->only('index');
        $this->middleware('permission:roles.store')->only('store');
        $this->middleware('permission:roles.show')->only('show');
        $this->middleware('permission:roles.update')->only('update');
        $this->middleware('permission:roles.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->collectionResponse(RoleResource::collection($this->getModel(new Role, ['permissions'])));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = new Role;
        $role->fill($request->all());

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        $role->saveOrFail();

        return $this->api_success([
            'data' => new RoleResource($role->load(['permissions'])),
            'message' => __('pages.responses.created'),
            'code' => 201
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function show(Role $role): JsonResponse
    {
        $role->load('permissions');
        return $this->singleResponse(new RoleResource($role));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        if ($request->has('name')) {
            $role->name = $request->name;
        }

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        $role->saveOrFail();
        return $this->api_success([
            'data'      =>  new RoleResource($role),
            'message'   => __('pages.responses.updated'),
            'code'      =>  200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Role $role): JsonResponse
    {
        $role->delete();
        return $this->api_success([
            'data' => new RoleResource($role),
            'message' => __('pages.responses.deleted'),
            'code' => 200
        ]);
    }
}
