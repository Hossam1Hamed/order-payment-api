<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Return a generic success response.
     */
    protected function successResponse(
        mixed $data = null,
        string $message = 'Success',
        int $statusCode = 200,
    ): JsonResponse {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }

    /**
     * Return a 201 Created response.
     */
    protected function createdResponse(
        mixed $data = null,
        string $message = 'Resource created successfully',
    ): JsonResponse {
        return $this->successResponse($data, $message, 201);
    }

    /**
     * Return a 204 No Content response.
     */
    protected function noContentResponse(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * Return a generic error response.
     */
    protected function errorResponse(
        string $message = 'An error occurred',
        int $statusCode = 400,
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

    /**
     * Return a 401 Unauthorized response.
     */
    protected function unauthorizedResponse(
        string $message = 'Unauthorized',
    ): JsonResponse {
        return $this->errorResponse($message, 401);
    }

    /**
     * Return a 403 Forbidden response.
     */
    protected function forbiddenResponse(
        string $message = 'Forbidden',
    ): JsonResponse {
        return $this->errorResponse($message, 403);
    }

    /**
     * Return a 404 Not Found response.
     */
    protected function notFoundResponse(
        string $message = 'Resource not found',
    ): JsonResponse {
        return $this->errorResponse($message, 404);
    }

    /**
     * Return a 422 Unprocessable Entity response (validation errors).
     */
    protected function validationErrorResponse(
        mixed $errors,
        string $message = 'Validation failed',
    ): JsonResponse {
        return $this->errorResponse($message, 422, $errors);
    }

    /**
     * Return a 500 Internal Server Error response.
     */
    protected function serverErrorResponse(
        string $message = 'Internal server error',
    ): JsonResponse {
        return $this->errorResponse($message, 500);
    }
}
