<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pemulangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PemulanganController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('start_date') && $request->get('end_date')) {
            $data = Pemulangan::whereBetween('tanggal', [$request->get('start_date'), $request->get('end_date')])->with(['penyediaJasa', 'jenisPengangkutan', 'user' => function ($query) {
                $query->select('id', 'nama');
            }, 'pmi'])->orderBy('created_at', 'desc')->get();
        } else {
            $data = Pemulangan::with(['penyediaJasa', 'jenisPengangkutan', 'user' => function ($query) {
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
        $data = Pemulangan::where('id', $id)->with(['penyediaJasa', 'jenisPengangkutan', 'user' => function ($query) {
            $query->select('id', 'nama');
        }, 'pmi'])->first();
        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_po' => 'required|string|max:255|unique:pemulangan,no_po',
            'id_penyedia_jasa' => 'required|string',
            'id_jenis_pengangkutan' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'tanggal' => 'required|date_format:Y-m-d',
            'durasi' => 'required|string',
        ], [], [
            'no_po' => 'Nomor Purchase Order',
            'id_penyedia_jasa' => 'Penyedia Jasa',
            'id_jenis_pengangkutan' => 'Jenis Pengangkutan',
            'lokasi' => 'Lokasi',
            'tanggal' => 'Tanggal',
            'durasi' => 'Durasi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $pemulangan = Pemulangan::create([
            'no_po' => $request->no_po,
            'id_penyedia_jasa' => $request->id_penyedia_jasa,
            'id_jenis_pengangkutan' => $request->id_jenis_pengangkutan,
            'lokasi' => $request->lokasi,
            'tanggal' => $request->tanggal,
            'durasi' => $request->durasi,
            'id_user' => auth()->id(),
        ]);

        $data = Pemulangan::where('id', $pemulangan->id)->with(['penyediaJasa', 'jenisPengangkutan', 'user' => function ($query) {
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
            'no_po' => 'required|string|max:255|unique:pemulangan,no_po,' . $id . ',id',
            'id_penyedia_jasa' => 'required|string',
            'id_jenis_pengangkutan' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'tanggal' => 'required|date_format:Y-m-d',
            'durasi' => 'required|string',
        ], [], [
            'no_po' => 'Nomor Purchase Order',
            'id_penyedia_jasa' => 'Penyedia Jasa',
            'id_jenis_pengangkutan' => 'Jenis Pengangkutan',
            'lokasi' => 'Lokasi',
            'tanggal' => 'Tanggal',
            'durasi' => 'Durasi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $pemulangan = Pemulangan::findOrfail($id);

        $pemulangan->update([
            'no_po' => $request->no_po,
            'id_penyedia_jasa' => $request->id_penyedia_jasa,
            'id_jenis_pengangkutan' => $request->id_jenis_pengangkutan,
            'lokasi' => $request->lokasi,
            'tanggal' => $request->tanggal,
            'durasi' => $request->durasi,
            'id_user' => auth()->id(),
        ]);

        $data = Pemulangan::where('id', $pemulangan->id)->with(['penyediaJasa', 'jenisPengangkutan', 'user' => function ($query) {
            $query->select('id', 'nama');
        }, 'pmi'])->first();

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function destroy($id)
    {
        $data = Pemulangan::findOrFail($id);

        $path = 'uploads/photos';
        if (File::exists(public_path($path . '/' . basename($data->photo_pemulangan)))) {
            File::delete(public_path($path . '/' . basename($data->photo_pemulangan)));
        }

        if (File::exists(public_path($path . '/' . basename($data->photo_invoice)))) {
            File::delete(public_path($path . '/' . basename($data->photo_invoice)));
        }

        $data->delete();
        return response()->json([
            'message' => 'Success',
        ], 200);
    }

    public function pmi(Request $request)
    {
        $pemulangan = Pemulangan::findOrFail($request->id_pemulangan);
        $pemulangan->pmi()->sync($request->id_pmi);

        $data = Pemulangan::where('id', $pemulangan->id)->with(['penyediaJasa', 'jenisPengangkutan', 'user' => function ($query) {
            $query->select('id', 'nama');
        }, 'pmi'])->first();

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function uploadPhotoPemulangan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo_pemulangan' => 'nullable|max:5000|mimes:jpg,jpeg,png',
        ], [], [
            'photo_pemulangan' => 'Foto Pemulangan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $pemulangan = Pemulangan::findOrFail($request->id_pemulangan);

        if ($request->hasFile('photo_pemulangan')) {
            $path = 'uploads/photos';

            if (File::exists(public_path($path . '/' . basename($pemulangan->photo_pemulangan)))) {
                File::delete(public_path($path . '/' . basename($pemulangan->photo_pemulangan)));
            }

            if (!File::isDirectory(public_path($path))) {
                File::makeDirectory(public_path($path), 0777, true, true);
            }

            $photo_pemulangan = 'pemulangan_' . time() . '.' . $request->photo_pemulangan->extension();
            if ($request->photo_pemulangan->move(public_path($path), $photo_pemulangan)) {
                $pemulangan->update([
                    'photo_pemulangan' => asset('') . $path . '/' . $photo_pemulangan
                ]);
            };
        }

        $data = Pemulangan::where('id', $pemulangan->id)->with(['penyediaJasa', 'jenisPengangkutan', 'user' => function ($query) {
            $query->select('id', 'nama');
        }, 'pmi'])->first();

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function uploadPhotoInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo_invoice' => 'nullable|max:5000|mimes:jpg,jpeg,png',
        ], [], [
            'photo_invoice' => 'Foto Invoice',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $pemulangan = Pemulangan::findOrFail($request->id_pemulangan);

        if ($request->hasFile('photo_invoice')) {
            $path = 'uploads/photos';

            if (File::exists(public_path($path . '/' . basename($pemulangan->photo_invoice)))) {
                File::delete(public_path($path . '/' . basename($pemulangan->photo_invoice)));
            }

            if (!File::isDirectory(public_path($path))) {
                File::makeDirectory(public_path($path), 0777, true, true);
            }

            $photo_invoice = 'invoice_' . time() . '.' . $request->photo_invoice->extension();
            if ($request->photo_invoice->move(public_path($path), $photo_invoice)) {
                $pemulangan->update([
                    'photo_invoice' => asset('') . $path . '/' . $photo_invoice
                ]);
            };
        }

        $data = Pemulangan::where('id', $pemulangan->id)->with(['penyediaJasa', 'jenisPengangkutan', 'user' => function ($query) {
            $query->select('id', 'nama');
        }, 'pmi'])->first();

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }
}
