<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Makan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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

        $path = 'uploads/photos';
        if (File::exists(public_path($path . '/' . basename($makan->photo_makan)))) {
            File::delete(public_path($path . '/' . basename($makan->photo_makan)));
        }

        if (File::exists(public_path($path . '/' . basename($makan->photo_invoice)))) {
            File::delete(public_path($path . '/' . basename($makan->photo_invoice)));
        }

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

    public function uploadPhotoMakan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo_makan' => 'nullable|max:5000|mimes:jpg,jpeg,png',
        ], [], [
            'photo_makan' => 'Foto Penerima Makan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $makan = Makan::findOrFail($request->id_makan);

        if ($request->hasFile('photo_makan')) {
            $path = 'uploads/photos';

            if (File::exists(public_path($path . '/' . basename($makan->photo_makan)))) {
                File::delete(public_path($path . '/' . basename($makan->photo_makan)));
            }

            if (!File::isDirectory(public_path($path))) {
                File::makeDirectory(public_path($path), 0777, true, true);
            }

            $photo_makan = 'makan_' . time() . '.' . $request->photo_makan->extension();
            if ($request->photo_makan->move(public_path($path), $photo_makan)) {
                $makan->update([
                    'photo_makan' => asset('') . $path . '/' . $photo_makan
                ]);
            };
        }

        $data = Makan::where('id', $request->id_makan)->with(['penyediaJasa', 'jenisBarang', 'user' => function ($query) {
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

        $makan = Makan::findOrFail($request->id_makan);

        if ($request->hasFile('photo_invoice')) {
            $path = 'uploads/photos';

            if (File::exists(public_path($path . '/' . basename($makan->photo_invoice)))) {
                File::delete(public_path($path . '/' . basename($makan->photo_invoice)));
            }

            if (!File::isDirectory(public_path($path))) {
                File::makeDirectory(public_path($path), 0777, true, true);
            }

            $photo_invoice = 'invoice_' . time() . '.' . $request->photo_invoice->extension();
            if ($request->photo_invoice->move(public_path($path), $photo_invoice)) {
                $makan->update([
                    'photo_invoice' => asset('') . $path . '/' . $photo_invoice
                ]);
            };
        }

        $data = Makan::where('id', $request->id_makan)->with(['penyediaJasa', 'jenisBarang', 'user' => function ($query) {
            $query->select('id', 'nama');
        }, 'pmi'])->first();

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }
}
