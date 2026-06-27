<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ApiExceptionHandler
{
    /**
     * Register all API exception renderers into the Laravel exception handler.
     */
    public static function register(Exceptions $exceptions): void
    {
        // ── 422 Validation ─────────────────────────────────────────────────
        $exceptions->render(function (ValidationException $e, Request $request): JsonResponse {
            return self::response(
                message: 'Validation failed',
                statusCode: 422,
                errors: $e->errors(),
            );
        });

        // ── 401 Unauthenticated ─────────────────────────────────────────────
        $exceptions->render(function (AuthenticationException $e, Request $request): JsonResponse {
            return self::response(
                message: 'Unauthenticated',
                statusCode: 401,
            );
        });

        // ── 403 Unauthorized ────────────────────────────────────────────────
        $exceptions->render(function (AuthorizationException $e, Request $request): JsonResponse {
            return self::response(
                message: $e->getMessage() ?: 'Forbidden',
                statusCode: 403,
            );
        });

        // ── 404 Model not found ─────────────────────────────────────────────
        $exceptions->render(function (ModelNotFoundException $e, Request $request): JsonResponse {
            $model = class_basename($e->getModel());

            return self::response(
                message: "{$model} not found",
                statusCode: 404,
            );
        });

        // ── 404 Route not found ─────────────────────────────────────────────
        $exceptions->render(function (NotFoundHttpException $e, Request $request): JsonResponse {
            return self::response(
                message: 'The requested endpoint does not exist',
                statusCode: 404,
            );
        });

        // ── 405 Method not allowed ──────────────────────────────────────────
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request): JsonResponse {
            return self::response(
                message: 'HTTP method not allowed',
                statusCode: 405,
            );
        });

        // ── 429 Too many requests ───────────────────────────────────────────
        $exceptions->render(function (TooManyRequestsHttpException $e, Request $request): JsonResponse {
            return self::response(
                message: 'Too many requests. Please slow down',
                statusCode: 429,
            );
        });

        // ── Generic HTTP exceptions ─────────────────────────────────────────
        $exceptions->render(function (HttpException $e, Request $request): JsonResponse {
            return self::response(
                message: $e->getMessage() ?: 'HTTP error',
                statusCode: $e->getStatusCode(),
            );
        });

        // ── 500 Database errors ─────────────────────────────────────────────
        $exceptions->render(function (QueryException $e, Request $request): JsonResponse {
            return self::response(
                message: 'A database error occurred',
                statusCode: 500,
            );
        });

        // ── 500 Catch-all ───────────────────────────────────────────────────
        $exceptions->render(function (Throwable $e, Request $request): JsonResponse {
            return self::response(
                message: app()->hasDebugModeEnabled()
                    ? $e->getMessage()
                    : 'An unexpected error occurred',
                statusCode: 500,
                errors: app()->hasDebugModeEnabled() ? [
                    'exception' => get_class($e),
                    'file'      => $e->getFile(),
                    'line'      => $e->getLine(),
                    'trace'     => collect($e->getTrace())->take(5)->toArray(),
                ] : null,
            );
        });
    }

    /**
     * Build a consistent JSON error envelope matching the ApiResponseTrait structure.
     */
    private static function response(
        string $message,
        int $statusCode,
        mixed $errors = null,
    ): JsonResponse {
        $body = [
            'status'  => 'error',
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $body['errors'] = $errors;
        }

        return response()->json($body, $statusCode);
    }
}
