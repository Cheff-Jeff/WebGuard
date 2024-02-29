<?php

use \Illuminate\Http\JsonResponse;
use \App\Models\User;
use Illuminate\Support\Facades\Validator;

function sendError($message, $code): JsonResponse
{
    return response()->json([
        "errors" => $message
    ], $code);
}

function accessDenied(): JsonResponse
{
    return sendError('You do not have permission to do this.', 401);
}

function missingParms(): JsonResponse
{
    return sendError("Missing parameters", 422);
}

function sendSuccess($message): JsonResponse
{
    return response()->json([
        "message" => $message
    ], 200);
}

function isSuperAdmin(User $user): bool
{
    return $user->role->role === 'super-admin';
}

function authorize($token): JsonResponse
{
    return response()->json([
        'status' => true,
        'message' => 'User Created Successfully',
        'token' => $token
    ], 200);
}

function validateRequest($params, $rules): bool
{
    $validate = Validator::make($params, $rules);

    if (!$validate->fails()) {
        return true;
    }

    return false;
}
