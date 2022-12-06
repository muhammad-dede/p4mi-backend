<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pmi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PmiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('start_date') && $request->get('end_date')) {
            $data = Pmi::whereBetween('tanggal_kembali', [$request->get('start_date'), $request->get('end_date')])->with(['provinsi', 'kota', 'statusKedatangan', 'makan', 'pemulangan', 'user' => function ($query) {
                $query->select('id', 'nama');
            }])->orderBy('created_at', 'desc')->get();
        } else {
            $data = Pmi::with(['provinsi', 'kota', 'statusKedatangan', 'makan', 'pemulangan', 'user' => function ($query) {
                $query->select('id', 'nama');
            }])->orderBy('created_at', 'desc')->get();
        }

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function show($id)
    {
        $pmi = Pmi::where('id', $id)->with(['provinsi', 'kota', 'statusKedatangan', 'makan', 'pemulangan', 'user' => function ($query) {
            $query->select('id', 'nama');
        }])->first();

        return response()->json([
            'message' => 'Success',
            'data' => $pmi,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_paspor' => 'required|string|max:255|unique:pmi,no_paspor',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date_format:Y-m-d',
            'alamat' => 'required|string',
            'id_provinsi' => 'required|string',
            'id_kota' => 'required|string',
            'telepon' => 'required|string|max:255',
            'negara_tempat_bekerja' => 'required|string|max:255',
            'tahun_bekerja' => 'required|max:4',
            'tanggal_kembali' => 'required|date_format:Y-m-d',
            'id_status_kedatangan' => 'required|string',
            'masalah' => 'nullable|string',
            'tuntutan' => 'nullable|string',
            'photo_pmi' => 'required|max:5000|mimes:jpg,jpeg,png',
            'photo_paspor' => 'required|max:5000|mimes:jpg,jpeg,png',
        ], [], [
            'no_paspor' => 'No Paspor',
            'nama' => 'Nama',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'alamat' => 'Alamat',
            'id_provinsi' => 'Provinsi',
            'id_kota' => 'Kota',
            'telepon' => 'No Telepon',
            'negara_tempat_bekerja' => 'Negara Tempat Bekerja',
            'tahun_bekerja' => 'Mulai bekerja di luar negeri',
            'tanggal_kembali' => 'Kembali ke dalam negeri',
            'id_status_kedatangan' => 'Status Kedatangan',
            'masalah' => 'Jenis Masalah',
            'tuntutan' => 'Tuntutan',
            'photo_pmi' => 'photo PMI',
            'photo_paspor' => 'photo Paspor',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $pmi = Pmi::create([
            'no_paspor' => $request->no_paspor,
            'nama' => ucfirst($request->nama),
            'tempat_lahir' => ucfirst($request->tempat_lahir),
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'id_provinsi' => $request->id_provinsi,
            'id_kota' => $request->id_kota,
            'telepon' => $request->telepon,
            'negara_tempat_bekerja' => $request->negara_tempat_bekerja,
            'tahun_bekerja' => $request->tahun_bekerja,
            'tanggal_kembali' => $request->tanggal_kembali,
            'id_status_kedatangan' => $request->id_status_kedatangan,
            'masalah' => $request->masalah,
            'tuntutan' => $request->tuntutan,
            'id_user' => auth()->id(),
        ]);

        if ($request->hasFile('photo_pmi')) {
            $path = 'uploads/photos';

            if (!File::isDirectory(public_path($path))) {
                File::makeDirectory(public_path($path), 0777, true, true);
            }

            $photo_pmi = 'pmi_' . time() . '.' . $request->photo_pmi->extension();
            if ($request->photo_pmi->move(public_path($path), $photo_pmi)) {
                $pmi->update([
                    'photo_pmi' => asset('') . $path . '/' . $photo_pmi
                ]);
            };
        }

        if ($request->hasFile('photo_paspor')) {
            $path = 'uploads/photos';

            if (!File::isDirectory(public_path($path))) {
                File::makeDirectory(public_path($path), 0777, true, true);
            }

            $photo_paspor = 'paspor_' . time() . '.' . $request->photo_paspor->extension();
            if ($request->photo_paspor->move(public_path($path), $photo_paspor)) {
                $pmi->update([
                    'photo_paspor' => asset('') . $path . '/' . $photo_paspor
                ]);
            };
        }

        $data = Pmi::where('id', $pmi->id)->with(['provinsi', 'kota', 'statusKedatangan', 'makan', 'pemulangan', 'user' => function ($query) {
            $query->select('id', 'nama');
        }])->first();

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'no_paspor' => 'required|string|max:255|unique:pmi,no_paspor,' . $id . ',id',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date_format:Y-m-d',
            'alamat' => 'required|string',
            'id_provinsi' => 'required|string',
            'id_kota' => 'required|string',
            'telepon' => 'required|string|max:255',
            'negara_tempat_bekerja' => 'required|string|max:255',
            'tahun_bekerja' => 'required|max:4',
            'tanggal_kembali' => 'required|date_format:Y-m-d',
            'id_status_kedatangan' => 'required|string',
            'masalah' => 'nullable|string',
            'tuntutan' => 'nullable|string',
            'photo_pmi' => 'nullable|max:5000|mimes:jpg,jpeg,png',
            'photo_paspor' => 'nullable|max:5000|mimes:jpg,jpeg,png',
        ], [], [
            'no_paspor' => 'No Paspor',
            'nama' => 'Nama',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'alamat' => 'Alamat',
            'id_provinsi' => 'Provinsi',
            'id_kota' => 'Kota',
            'telepon' => 'No Telepon',
            'negara_tempat_bekerja' => 'Negara Tempat Bekerja',
            'tahun_bekerja' => 'Mulai bekerja di luar negeri',
            'tanggal_kembali' => 'Kembali ke dalam negeri',
            'id_status_kedatangan' => 'Status Kedatangan',
            'masalah' => 'Jenis Masalah',
            'tuntutan' => 'Tuntutan',
            'photo_pmi' => 'photo PMI',
            'photo_paspor' => 'photo Paspor',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $pmi = Pmi::findOrFail($id);

        $pmi->update([
            'no_paspor' => $request->no_paspor,
            'nama' => ucfirst($request->nama),
            'tempat_lahir' => ucfirst($request->tempat_lahir),
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'id_provinsi' => $request->id_provinsi,
            'id_kota' => $request->id_kota,
            'telepon' => $request->telepon,
            'negara_tempat_bekerja' => $request->negara_tempat_bekerja,
            'tahun_bekerja' => $request->tahun_bekerja,
            'tanggal_kembali' => $request->tanggal_kembali,
            'id_status_kedatangan' => $request->id_status_kedatangan,
            'masalah' => $request->masalah,
            'tuntutan' => $request->tuntutan,
            'id_user' => auth()->id(),
        ]);

        if ($request->hasFile('photo_pmi')) {
            $path = 'uploads/photos';

            if ($pmi->photo_pmi && basename($pmi->photo_pmi) !== 'user_blank.jpg') {
                if (File::exists(public_path($path . '/' . basename($pmi->photo_pmi)))) {
                    File::delete(public_path($path . '/' . basename($pmi->photo_pmi)));
                }
            }

            if (!File::isDirectory(public_path($path))) {
                File::makeDirectory(public_path($path), 0777, true, true);
            }

            $photo_pmi = 'pmi_' . time() . '.' . $request->photo_pmi->extension();
            if ($request->photo_pmi->move(public_path($path), $photo_pmi)) {
                $pmi->update([
                    'photo_pmi' => asset('') . $path . '/' . $photo_pmi
                ]);
            };
        }

        if ($request->hasFile('photo_paspor')) {
            $path = 'uploads/photos';

            if ($pmi->photo_paspor && basename($pmi->photo_paspor) !== 'paspor_blank.png') {
                if (File::exists(public_path($path . '/' . basename($pmi->photo_paspor)))) {
                    File::delete(public_path($path . '/' . basename($pmi->photo_paspor)));
                }
            }

            if (!File::isDirectory(public_path($path))) {
                File::makeDirectory(public_path($path), 0777, true, true);
            }

            $photo_paspor = 'paspor_' . time() . '.' . $request->photo_paspor->extension();
            if ($request->photo_paspor->move(public_path($path), $photo_paspor)) {
                $pmi->update([
                    'photo_paspor' => asset('') . $path . '/' . $photo_paspor
                ]);
            };
        }

        $data = Pmi::where('id', $pmi->id)->with(['provinsi', 'kota', 'statusKedatangan', 'makan', 'pemulangan', 'user' => function ($query) {
            $query->select('id', 'nama');
        }])->first();

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function destroy($id)
    {
        $pmi = Pmi::findOrFail($id);

        if ($pmi->photo_pmi && basename($pmi->photo_pmi) !== 'user_blank.jpg') {

            $path = 'uploads/photos';

            if (File::exists(public_path($path . '/' . basename($pmi->photo_pmi)))) {
                File::delete(public_path($path . '/' . basename($pmi->photo_pmi)));
            }
        }

        if ($pmi->photo_paspor && basename($pmi->photo_paspor) !== 'paspor_blank.png') {

            $path = 'uploads/photos';

            if (File::exists(public_path($path . '/' . basename($pmi->photo_paspor)))) {
                File::delete(public_path($path . '/' . basename($pmi->photo_paspor)));
            }
        }

        $pmi->delete();

        return response()->json([
            'message' => 'Success',
        ], 200);
    }
}
