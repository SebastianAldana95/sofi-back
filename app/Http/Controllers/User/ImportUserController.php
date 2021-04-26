<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Api\ApiController;
use App\Imports\UserImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportUserController extends ApiController
{
    public function import(Request $request): JsonResponse
    {
        Excel::import(new UserImport, $request->file('file'));
        return $this->api_success([
           'message' => 'Usuarios importados correctamente!',
           'code' => 200,
        ]);
    }
}
