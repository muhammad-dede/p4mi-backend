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
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:user,nip',
            'pangkat_golongan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'username' => 'required|string|alpha_dash|unique:user,username',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|string|min:8',
            'is_admin' => 'boolean',
        ], [], [
            'nama' => 'Nama',
            'nip' => 'NIP',
            'pangkat_golongan' => 'Pangkat/Golongan',
            'jabatan' => 'Jabatan',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'is_admin' => 'Is Admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $user = User::create([
            'nama' => ucfirst($request->nama),
            'nip' => $request->nip,
            'pangkat_golongan' => $request->pangkat_golongan,
            'jabatan' => $request->jabatan,
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
            'password' => bcrypt($request->password),
            'is_admin' => $request->is_admin,
        ]);

        return response()->json([
            'message' => 'Success',
            'data' => $user,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:user,nip,' . $id . ',id',
            'pangkat_golongan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'username' => 'required|string|alpha_dash|unique:user,username,' . $id . ',id',
            'email' => 'required|email|unique:user,email,' . $id . ',id',
            'password' => 'nullable|string|min:8',
            'is_admin' => 'boolean',
        ], [], [
            'nama' => 'Nama',
            'nip' => 'NIP',
            'pangkat_golongan' => 'Pangkat/Golongan',
            'jabatan' => 'Jabatan',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'is_admin' => 'Is Admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $user = User::findOrFail($id);

        $user->update([
            'nama' => ucfirst($request->nama),
            'nip' => $request->nip,
            'pangkat_golongan' => $request->pangkat_golongan,
            'jabatan' => $request->jabatan,
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'is_admin' => $request->is_admin,
        ]);

        return response()->json([
            'message' => 'Success',
            'data' => $user,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'message' => 'Success',
        ], 200);
    }
}
