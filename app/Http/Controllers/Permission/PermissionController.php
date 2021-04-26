<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;

class PermissionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:permissions.index')->only('index');
        $this->middleware('permission:permissions.store')->only('store');
        $this->middleware('permission:permissions.show')->only('show');
        $this->middleware('permission:permissions.update')->only('update');
        $this->middleware('permission:permissions.delete')->only('destroy');

    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->collectionResponse(PermissionResource::collection($this->getModel(new Permission, [])));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
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
     * @throws \Throwable
     */
    public function store(StorePermissionRequest $request): JsonResponse
    {
        $permission = new Permission;
        $permission->fill($request->all());
        $permission->guard_name = 'api';
        $permission->saveOrFail();
        return $this->api_success([
            'data' => new PermissionResource($permission),
            'message' => __('pages.responses.created'),
            'code' => 201
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param Permission $permission
     * @return JsonResponse
     */
    public function show(Permission $permission): JsonResponse
    {
        return $this->singleResponse(new PermissionResource($permission));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePermissionRequest $request
     * @param Permission $permission
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        if ($request->has('name')) {
            $permission->name = $request->name;
        }

        if ($request->has('description')) {
            $permission->description = $request->description;
        }

        if (!$permission->isDirty()) {
            return $this->errorResponse(
                'Se debe especificar al menos un valor diferente para actualizar',
            );
        }

        $permission->saveOrFail();
        return $this->api_success([
            'data'      =>  new PermissionResource($permission),
            'message'   => __('pages.responses.updated'),
            'code'      =>  200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Permission $permission
     * @return JsonResponse
     */
    public function destroy(Permission $permission): JsonResponse
    {
        $permission->delete();
        return $this->api_success([
            'data' => new PermissionResource($permission),
            'message' => __('pages.responses.deleted'),
            'code' => 200
        ]);
    }
}
