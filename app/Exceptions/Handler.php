<?php

namespace App\Exceptions;

use App\Helpers\ResponseCodes;
use App\Helpers\ResponseHelper;
use App\Helpers\ResponseMessages;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
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
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof CustomException) {
            return ResponseHelper::createErrorResponse($e->getMessage(), $e->getCode(), [
                'errors' => $e->getErrorMessages()
            ]);
        } elseif ($e instanceof ValidationException) {//handle validation errors
            $data = ["errors" => $e->validator->getMessageBag()->getMessages()];
            return ResponseHelper::createErrorResponse(ResponseMessages::FAILED_VALIDATION, ResponseCodes::FAILED_VALIDATION, $data, ResponseCodes::UNPROCESSABLE_ENTITY);
        } elseif ($e instanceof ModelNotFoundException) {
            return ResponseHelper::createErrorResponse(
                ResponseMessages::RESOURCE_NOT_FOUND, ResponseCodes::RESOURCE_NOT_FOUND
            );
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return ResponseHelper::createErrorResponse(
                ResponseMessages::ROUTE_NOT_FOUND, ResponseCodes::ROUTE_NOT_FOUND, [], 404
            );
        } elseif ($e instanceof DevException) {
            $data = ['contextData' => $e->getContextData()];
            return ResponseHelper::createErrorResponse($e->getUserMessage(), $e->getCode(), $data);
        } elseif ($e instanceof AuthenticationException) {
            return ResponseHelper::createErrorResponse($e->getMessage(), ResponseCodes::RESOURCE_AUTHORISATION_ERROR, [], ResponseCodes::UNAUTHENTICATED);
        }elseif ($e instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            return ResponseHelper::createErrorResponse(ResponseMessages::PERMISSION_DENIED, ResponseCodes::PERMISSION_DENIED, [], ResponseCodes::PERMISSION_DENIED);
        } else {
            return ResponseHelper::createErrorResponse(
                ResponseMessages::EXCEPTION_THROWN, ResponseCodes::EXCEPTION_THROWN,
                [
                    "error_message" => $e->getMessage(),
                    "error" => in_array(env('APP_ENV'), ['testing', 'staging', 'local']) ? $e->getTrace() : []
                ]
            );
        }
    }
}
