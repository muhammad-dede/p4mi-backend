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
            'user' => 'required|string',
            'password' => 'required|string',
        ], [], [
            'user' => 'Username/Email',
            'password' => 'Password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $user = User::where('username', $request->user)->orWhere('email', $request->user)->first();

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
                $token = $user->createToken('Access Token')->plainTextToken;
                return response()->json([
                    'message' => 'Success',
                    'data' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ], 200);
            }
        }
    }

    public function me()
    {
        $user = User::findOrFail(auth()->id());
        return response()->json([
            'message' => 'Success',
            'data' => $user,
        ], 200);
    }

    public function profilUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:user,nip,' . auth()->id() . ',id',
            'pangkat_golongan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'username' => 'required|alpha_dash|unique:user,username,' . auth()->id() . ',id',
            'email' => 'required|email|unique:user,email,' . auth()->id() . ',id',
        ], [], [
            'nama' => 'Nama',
            'nip' => 'NIP',
            'pangkat_golongan' => 'Pangkat/Golangan',
            'jabatan' => 'Jabatan',
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
            'nama' => $request->nama,
            'nip' => $request->nip,
            'pangkat_golongan' => $request->pangkat_golongan,
            'jabatan' => $request->jabatan,
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
        ]);

        return response()->json([
            'message' => 'Success',
            'data' => $user,
        ], 200);
    }

    public function passwordUpdate(Request $request)
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
            'message' => 'Success',
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Success',
        ], 200);
    }
}
