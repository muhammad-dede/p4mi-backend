<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\JenisPengangkutan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisPengangkutanController extends Controller
{
    public function index()
    {
        $data = JenisPengangkutan::orderBy('nama', 'asc')->get();
        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ], [], [
            'nama' => 'Nama',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $data = JenisPengangkutan::create([
            'nama' => ucfirst($request->nama),
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
        ], [], [
            'nama' => 'Nama',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $data = JenisPengangkutan::findOrFail($id);

        $data->update([
            'nama' => ucfirst($request->nama),
        ]);

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function destroy($id)
    {
        $data = JenisPengangkutan::findOrFail($id);
        $data->delete();

        return response()->json([
            'message' => 'Success',
        ], 200);
    }
}
