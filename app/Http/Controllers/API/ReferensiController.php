<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\JenisBarang;
use App\Models\JenisPengangkutan;
use App\Models\Kota;
use App\Models\PenyediaJasa;
use App\Models\Provinsi;
use App\Models\StatusKedatangan;
use App\Models\StatusPemulangan;
use Illuminate\Http\Request;

class ReferensiController extends Controller
{
    public function provinsi()
    {
        $data = Provinsi::orderBy('nama', 'asc')->get();
        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function kota(Request $request)
    {
        if ($request->id_provinsi) {
            $data = Kota::where('id_provinsi', $request->id_provinsi)->orderBy('nama', 'asc')->get();
        } else {
            $data = Kota::orderBy('nama', 'asc')->get();
        }
        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function statusKedatangan()
    {
        $data = StatusKedatangan::orderBy('id', 'asc')->get();
        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function statusPemulangan()
    {
        $data = StatusPemulangan::orderBy('id', 'asc')->get();
        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function penyediaJasa()
    {
        $data = PenyediaJasa::orderBy('id', 'asc')->get();
        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function jenisBarang()
    {
        $data = JenisBarang::orderBy('id', 'asc')->get();
        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function jenisPengangkutan()
    {
        $data = JenisPengangkutan::orderBy('id', 'asc')->get();
        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }
}
