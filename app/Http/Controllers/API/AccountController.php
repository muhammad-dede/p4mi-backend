<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|alpha_dash|unique:users,username,' . auth()->id() . ',id',
            'email' => 'required|email|unique:users,email,' . auth()->id() . ',id',
        ], [], [
            'name' => 'Nama',
            'username' => 'Username',
            'email' => 'Email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $user = User::findOrFail(auth()->id());

        $user->update([
            'name' => $request->name,
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
        ]);

        return response()->json([
            'message' => 'Update successfully',
            'data' => $user,
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password_current' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [], [
            'password_current' => 'Password saat ini',
            'password' => 'Password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $user = User::findOrFail(auth()->id());

        if (!Hash::check($request->password_current, $user->password)) {
            return response()->json([
                'message' => 'Password saat ini tidak cocok',
            ], 400);
        }

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'message' => 'Update successfully',
        ], 200);
    }
}
