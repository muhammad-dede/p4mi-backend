<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Makan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MakanController extends Controller
{
    public function index()
    {
        $data = Makan::orderBy('created_at', 'desc')->get();
        return response()->json([
            'message' => 'Fetch successfully',
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'po' => 'required|string|max:255|unique:makan,po',
            'tgl_antar' => 'required|date_format:Y-m-d',
            'waktu_antar' => 'required|time',
        ], [], [
            'po' => 'Nomor Purchase Order',
            'tgl_antar' => 'Tanggal Antar Barang',
            'waktu_antar' => 'Waktu Antar Barang'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $makan = Makan::create([
            'po' => $request->po,
            'tgl_antar' => $request->tgl_antar,
            'waktu_antar' => $request->waktu_antar,
            'id_user' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Create successfully',
            'data' => $makan,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'po' => 'required|string|max:255|unique:makan,po,' . $id . ',id',
            'tgl_antar' => 'required|date_format:Y-m-d',
            'waktu_antar' => 'required|time',
        ], [], [
            'po' => 'Nomor Purchase Order',
            'tgl_antar' => 'Tanggal Antar Barang',
            'waktu_antar' => 'Waktu Antar Barang'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $makan = Makan::findOrFail($id);

        $makan->update([
            'po' => $request->po,
            'tgl_antar' => $request->tgl_antar,
            'waktu_antar' => $request->waktu_antar,
            'id_user' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Update successfully',
            'data' => $makan,
        ], 200);
    }

    public function destroy($id)
    {
        $makan = Makan::findOrFail($id);
        $makan->delete();
        return response()->json([
            'message' => 'Delete successfully',
        ], 200);
    }
}
