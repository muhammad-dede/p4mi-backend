<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index()
    {
        $data = User::where('id', '!=', auth()->id())->orderBy('created_at', 'desc')->get();
        return response()->json([
            'message' => 'Fetch successfully',
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|alpha_dash|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|in:admin,petugas',
            'password' => 'required|string|min:8',
        ], [], [
            'name' => 'Nama',
            'username' => 'Username',
            'email' => 'Email',
            'role' => 'Role',
            'password' => 'Password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $user = User::create([
            'name' => ucfirst($request->name),
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'message' => 'Create successfully',
            'data' => $user,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|alpha_dash|unique:users,username,' . $id . ',id',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
            'role' => 'required|string|in:admin,petugas',
            'password' => 'nullable|string|min:8',
        ], [], [
            'name' => 'Nama',
            'username' => 'Username',
            'email' => 'Email',
            'role' => 'Role',
            'password' => 'Password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $user = User::findOrFail($id);

        $user->update([
            'name' => ucfirst($request->name),
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
            'role' => $request->role,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        return response()->json([
            'message' => 'Update successfully',
            'data' => $user,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'message' => 'Delete successfully',
        ], 200);
    }
}
