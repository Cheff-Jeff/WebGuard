<?php

use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use \App\Models\User;

function sendError($message, $code): JsonResponse
{
    return response()->json([
        "errors" => $message
    ], $code);
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
