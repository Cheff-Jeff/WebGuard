<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private array $updateUserParams = ['name', 'email', 'newPassword', 'oldPassword'];

    public function index()
    {
        return response()->json(User::all());
    }

    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
    {
        $validateRequest = Validator::make($request->only($this->updateUserParams), [
            'name' => 'required',
            'email' => 'required|email',
            'oldPassword' => 'required'
        ]);

        if ($validateRequest->fails()) {
            return sendError("Missing parameters", 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user === null || Hash::check($request->oldPassword, $user->password)) {
            return sendError("User not found", 404);
        }

        $user->fill($request->only(['name', 'email']));

        if (isset($request->newPassword)) {
            $user->fill(['password' => Hash::make(
                $request->newPassword, ['rounds' => 12]
            )]);
        }

        $user->save();
        return sendSuccess('User updated');
    }


    public function destroyUser(Request $request)
    {
        if (!validateRequest($request->only(['userId']), ['userId' => 'required|integer'])) {
            return missingParms();
        }

        if ($request->user()->role->role === 'editor') {
            return accessDenied();
        }

        $user = User::findOrFail($request->userId);

        if ($request->user()->id === $user->id) {
            return sendError('You can not delete yourself', 401);
        }

        $user->tokens()->delete();
        $user->delete();
        return sendSuccess("User deleted.");
    }
}
