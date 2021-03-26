<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use Yajra\Pdo\Oci8\Exceptions\Oci8Exception;

class Handler extends ExceptionHandler
{

    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelo = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No existe ningun objeto de {$modelo} con el id especificado", 404);
        }

        if ($exception instanceof RouteNotFoundException) {
            return $this->errorResponse("Ruta no encontrada", 404);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse('No tiene permisos para realizar esta acción', 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('No se encontró la URL especificada', 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('El metodo especificado en la peticion no es valido', 405);
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof QueryException) {
            $code = $exception->getCode();
            if ($code === 2292) {
                return $this->errorResponse('No se puede eliminar de forma permanente el recurso porque está relacionado con algún otro.',
                    409);
            }
        }

        if ($exception instanceof Oci8Exception) {
            $code = $exception->getCode();
            if ($code === 2291) {
                return $this->errorResponse('No se puede insertar el recurso porque la llave foranea no fue encontrada.',
                    409);
            }
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }


        return $this->errorResponse('Error inesperado, Intente mas tarde.', 500);


    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('Usuario no autenticado', 401);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->messages();
        return $this->errorResponse($errors, 422);
    }
}
