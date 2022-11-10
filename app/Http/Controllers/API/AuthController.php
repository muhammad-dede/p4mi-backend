<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|alpha_dash|exists:users,username',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Credential tidak terdaftar',
            ], 400);
        } else {
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Password tidak cocok',
                ], 400);
            } else {
                $user->tokens()->delete();
                $token = $user->createToken('Personal Access Token')->plainTextToken;
                return response()->json([
                    'message' => 'Login successfully',
                    'data' => [
                        'token' => $token,
                        'user' => $user,
                    ],
                ], 200);
            }
        }
    }

    public function me()
    {
        $user = User::findOrFail(auth()->id());
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Fetch successfully',
            'data' => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successfully',
        ], 200);
    }
}
