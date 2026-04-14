<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;


class BaseApiController extends Controller
{
    /** @var $service */
    public $service;

    /** @var string */
    public $viewDir;

    // ==========================================
    // Success Response
    // ==========================================

    public function successResponse(
        $data,
        $message    = null,
        $api_token  = null,
        $token_type = null,
        int $code   = 200): JsonResponse
    {
        $response = [
            'status'  => true,
            'message' => $message ?? Config::get('constants.generalMessages.records_found'),
            'data'    => $data,
        ];
        if ($api_token) {
            $response['api_token']  = $api_token;
            $response['token_type'] = $token_type;
        }
        return response()->json($response, $code);
    }

    // ==========================================
    // Error Response
    // ==========================================

    public function errorResponse($message = null, int $code = 400): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'message' => $message ?? Config::get('constants.generalMessages.something_wrong'),
            'data'    => null,
        ], $code);
    }

    // ==========================================
    // Validation Error Response
    // ==========================================

    public function validationErrorResponse($errors, $message = null): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'message' => $message ?? Config::get('constants.validationMessages.failed'),
            'errors'  => $errors,
        ], 422);
    }

    // ==========================================
    // Not Found Response
    // ==========================================

    public function notFoundResponse($message = null, int $code = 404): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'message' => $message ?? Config::get('constants.generalMessages.not_found'),
            'data'    => null,
        ], $code);
    }

    // ==========================================
    // Unauthorized Response
    // ==========================================

    public function unauthorizedResponse($message = null): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'message' => $message ?? Config::get('constants.generalMessages.unauthorized'),
            'data'    => null,
        ], 401);
    }

    // ==========================================
    // Forbidden Response
    // ==========================================

    public function forbiddenResponse($message = null): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'message' => $message ?? Config::get('constants.generalMessages.forbidden'),
            'data'    => null,
        ], 403);
    }

    // ==========================================
    // Paginated Response
    // ==========================================

    public function paginateResponse($data, $message = null): JsonResponse
    {
        return response()->json([
            'status'  => true,
            'message' => $message ?? Config::get('constants.generalMessages.records_found'),
            'data'    => $data->items(),
            'pagination' => [
                'total'        => $data->total(),
                'per_page'     => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page'    => $data->lastPage(),
                'from'         => $data->firstItem(),
                'to'           => $data->lastItem(),
            ],
        ], 200);
    }

    // ==========================================
    // Created Response
    // ==========================================

    public function createdResponse(
        $data,
        $message    = null,
        $api_token  = null,
        $token_type = null
    ): JsonResponse {
        $response = [
            'status'  => true,
            'message' => $message ?? Config::get('constants.adminMessages.store_success'),
            'data'    => $data,
        ];

        if ($api_token) {
            $response['api_token']  = $api_token;
            $response['token_type'] = $token_type;
        }

        return response()->json($response, 201);
    }

    // ==========================================
    // Deleted Response
    // ==========================================

    public function deletedResponse($message = null): JsonResponse
    {
        return response()->json([
            'status'  => true,
            'message' => $message ?? Config::get('constants.adminMessages.delete_success'),
            'data'    => null,
        ], 200);
    }
    // ==========================================
    // File Upload Helper
    // ==========================================

    public function uploadFile($file, $path = 'uploads'): string
    {
        $name    = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $stored  = \Storage::disk('public')->putFileAs($path, $file, $name);
        return $stored;
    }

    // ==========================================
    // Get Authenticated User
    // ==========================================

    public function authUser()
    {
        return auth()->user();
    }
    // ==========================================
    // Get Custom Message
    // ==========================================
        public function getMessage($key): string
    {
        return Config::get('constants.' . $key) ?? 'Something went wrong';
    }
}
