<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PenyediaJasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenyediaJasaController extends Controller
{
    public function index()
    {
        $data = PenyediaJasa::orderBy('nama', 'asc')->get();
        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:penyedia_jasa,email',
            'telp' => 'nullable|string|max:255',
            'up' => 'required|string|max:255',
        ], [], [
            'nama' => 'Nama',
            'alamat' => 'Alamat',
            'email' => 'Email',
            'telp' => 'Telepon',
            'up' => 'UP',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $data = PenyediaJasa::create([
            'nama' => ucfirst($request->nama),
            'alamat' => $request->alamat,
            'email' => $request->email,
            'telp' => $request->telp,
            'up' => $request->up,
        ]);

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:penyedia_jasa,email,' . $id . ',id',
            'telp' => 'nullable|string|max:255',
            'up' => 'required|string|max:255',
        ], [], [
            'nama' => 'Nama',
            'alamat' => 'Alamat',
            'email' => 'Email',
            'telp' => 'Telepon',
            'up' => 'UP',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $data = PenyediaJasa::findOrFail($id);

        $data->update([
            'nama' => ucfirst($request->nama),
            'alamat' => $request->alamat,
            'email' => $request->email,
            'telp' => $request->telp,
            'up' => $request->up,
        ]);

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function destroy($id)
    {
        $data = PenyediaJasa::findOrFail($id);
        $data->delete();

        return response()->json([
            'message' => 'Success',
        ], 200);
    }
}
