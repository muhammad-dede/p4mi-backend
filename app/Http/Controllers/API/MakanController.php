<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Makan;
use App\Models\Pmi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MakanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('start_date') && $request->get('end_date')) {
            $data = Makan::whereBetween('tanggal', [$request->get('start_date'), $request->get('end_date')])->with(['penyediaJasa', 'jenisBarang', 'user' => function ($query) {
                $query->select('id', 'nama');
            }, 'pmi'])->orderBy('created_at', 'desc')->get();
        } else {
            $data = Makan::with(['penyediaJasa', 'jenisBarang', 'user' => function ($query) {
                $query->select('id', 'nama');
            }, 'pmi'])->orderBy('created_at', 'desc')->get();
        }

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function detail($id)
    {
        $makan = Makan::where('id', $id)->with(['penyediaJasa', 'jenisBarang', 'user' => function ($query) {
            $query->select('id', 'nama');
        }, 'pmi'])->first();
        return response()->json([
            'message' => 'Success',
            'data' => $makan,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_po' => 'required|string|max:255|unique:makan,no_po',
            'id_penyedia_jasa' => 'required|string',
            'id_jenis_barang' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'tanggal' => 'required|date_format:Y-m-d',
            'waktu' => 'required|string',
            'durasi' => 'required|string',
        ], [], [
            'no_po' => 'Nomor Purchase Order',
            'id_penyedia_jasa' => 'Penyedia Jasa',
            'id_jenis_barang' => 'Jenis Barang',
            'lokasi' => 'Lokasi',
            'tanggal' => 'Tanggal',
            'waktu' => 'Waktu',
            'durasi' => 'Durasi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $makan = Makan::create([
            'no_po' => $request->no_po,
            'id_penyedia_jasa' => $request->id_penyedia_jasa,
            'id_jenis_barang' => $request->id_jenis_barang,
            'lokasi' => $request->lokasi,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'durasi' => $request->durasi,
            'id_user' => auth()->id(),
        ]);

        $data = Makan::where('id', $makan->id)->with(['penyediaJasa', 'jenisBarang', 'user' => function ($query) {
            $query->select('id', 'nama');
        }, 'pmi'])->first();

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'no_po' => 'required|string|max:255|unique:makan,no_po,' . $id . ',id',
            'id_penyedia_jasa' => 'required|string',
            'id_jenis_barang' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'tanggal' => 'required|date_format:Y-m-d',
            'waktu' => 'required|string',
            'durasi' => 'required|string',
        ], [], [
            'no_po' => 'Nomor Purchase Order',
            'id_penyedia_jasa' => 'Penyedia Jasa',
            'id_jenis_barang' => 'Jenis Barang',
            'lokasi' => 'Lokasi',
            'tanggal' => 'Tanggal',
            'waktu' => 'Waktu',
            'durasi' => 'Durasi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $makan = Makan::findOrFail($id);

        $makan->update([
            'no_po' => $request->no_po,
            'id_penyedia_jasa' => $request->id_penyedia_jasa,
            'id_jenis_barang' => $request->id_jenis_barang,
            'lokasi' => $request->lokasi,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'durasi' => $request->durasi,
            'id_user' => auth()->id(),
        ]);

        $data = Makan::where('id', $makan->id)->with(['penyediaJasa', 'jenisBarang', 'user' => function ($query) {
            $query->select('id', 'nama');
        }, 'pmi'])->first();

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function destroy($id)
    {
        $makan = Makan::findOrFail($id);
        $makan->delete();
        return response()->json([
            'message' => 'Success',
        ], 200);
    }

    public function pmi(Request $request)
    {
        $makan = Makan::findOrFail($request->id_makan);
        $makan->pmi()->sync($request->id_pmi);

        $data = Makan::where('id', $request->id_makan)->with(['penyediaJasa', 'jenisBarang', 'user' => function ($query) {
            $query->select('id', 'nama');
        }, 'pmi'])->first();

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }
}
