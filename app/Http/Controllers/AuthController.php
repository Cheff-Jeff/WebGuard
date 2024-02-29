<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private array $signInParams = ['email', 'password'];
    private array $signUpParams = ['name', 'email', 'password'];

    public function signUp(Request $request)
    {
        try {
            $validateRequest = Validator::make($request->only($this->signUpParams), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if ($validateRequest->fails() || $request->user() === null){
                return sendError("Missing parameters", 422);
            }

            if (!isSuperAdmin($request->user())) {
                return sendError("No permission", 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => UserRole::where('role', 'editor')->first()->getId()
            ]);

            return authorize($user->createToken('apiToken')->plainTextToken);
        }
        catch (\Throwable $th) {
            return sendError([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function signIn(Request $request): JsonResponse
    {
        try {
            $validateRequest = Validator::make($request->only($this->signInParams), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validateRequest->fails()) {
                return sendError("Missing parameters", 422);
            }

            if (!Auth::attempt($request->only($this->signInParams))) {
                return sendError("Authentication failed", 404);
            }

            $user = User::where('email', $request->email)->first();

            return authorize($user->createToken('apiToken')->plainTextToken);
        }
        catch (\Throwable $th) {
            return sendError([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function signOff(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return sendSuccess('Signed off.');
    }
}
