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
        $per_page = $request->get('per_page') ? $request->get('per_page') : 10;

        $data = Pmi::with(['provinsi', 'kota', 'statusKedatangan', 'user' => function ($query) {
            $query->select('id', 'name');
        }])->orderBy('created_at', 'desc')->paginate($per_page);

        return response()->json([
            'message' => 'Fetch successfully',
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_paspor' => 'required|string|max:255|unique:pmi,no_paspor',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'alamat' => 'required|string',
            'id_provinsi' => 'required|string',
            'id_kota' => 'required|string',
            'no_telp' => 'required|string|max:255',
            'negara_tempat_bekerja' => 'required|string|max:255',
            'tahun_bekerja' => 'required|max:4',
            'tgl_kembali' => 'required|date_format:Y-m-d',
            'id_status_kedatangan' => 'required|string',
            'masalah' => 'nullable|string',
            'tuntutan' => 'nullable|string',
            'foto_pmi' => 'required|max:2048|mimes:jpg,jpeg,png',
            'foto_paspor' => 'required|max:2048|mimes:jpg,jpeg,png',
        ], [], [
            'no_paspor' => 'No Paspor',
            'nama' => 'Nama',
            'tempat_lahir' => 'Tempat Lahir',
            'tgl_lahir' => 'Tanggal Lahir',
            'alamat' => 'Alamat',
            'id_provinsi' => 'Provinsi',
            'id_kota' => 'Kota',
            'no_telp' => 'No Telepon',
            'negara_tempat_bekerja' => 'Negara Tempat Bekerja',
            'tahun_bekerja' => 'Mulai bekerja di luar negeri',
            'tgl_kembali' => 'Kembali ke dalam negeri',
            'id_status_kedatangan' => 'Status Kedatangan',
            'masalah' => 'Jenis Masalah',
            'tuntutan' => 'Tuntutan',
            'foto_pmi' => 'Foto PMI',
            'foto_paspor' => 'Foto Paspor',
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
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat,
            'id_provinsi' => $request->id_provinsi,
            'id_kota' => $request->id_kota,
            'no_telp' => $request->no_telp,
            'negara_tempat_bekerja' => $request->negara_tempat_bekerja,
            'tahun_bekerja' => $request->tahun_bekerja,
            'tgl_kembali' => $request->tgl_kembali,
            'id_status_kedatangan' => $request->id_status_kedatangan,
            'masalah' => $request->masalah,
            'tuntutan' => $request->tuntutan,
            'id_user' => auth()->id(),
        ]);

        if ($request->hasFile('foto_pmi')) {
            $path = 'uploads/photo';

            if (!File::isDirectory(public_path($path))) {
                File::makeDirectory(public_path($path), 0777, true, true);
            }

            $foto_pmi = 'pmi_' . time() . '.' . $request->foto_pmi->extension();
            if ($request->foto_pmi->move(public_path($path), $foto_pmi)) {
                $pmi->update([
                    'foto_pmi' => asset('') . $path . '/' . $foto_pmi
                ]);
            };
        }

        if ($request->hasFile('foto_paspor')) {
            $path = 'uploads/paspor';

            if (!File::isDirectory(public_path($path))) {
                File::makeDirectory(public_path($path), 0777, true, true);
            }

            $foto_paspor = 'paspor_' . time() . '.' . $request->foto_paspor->extension();
            if ($request->foto_paspor->move(public_path($path), $foto_paspor)) {
                $pmi->update([
                    'foto_paspor' => asset('') . $path . '/' . $foto_paspor
                ]);
            };
        }

        return response()->json([
            'message' => 'Create successfully',
            'data' => $pmi,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'no_paspor' => 'required|string|max:255|unique:pmi,no_paspor,' . $id . ',id',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'alamat' => 'required|string',
            'id_provinsi' => 'required|string',
            'id_kota' => 'required|string',
            'no_telp' => 'required|string|max:255',
            'negara_tempat_bekerja' => 'required|string|max:255',
            'tahun_bekerja' => 'required|max:4',
            'tgl_kembali' => 'required|date_format:Y-m-d',
            'id_status_kedatangan' => 'required|string',
            'masalah' => 'nullable|string',
            'tuntutan' => 'nullable|string',
            'foto_pmi' => 'nullable|max:2048|mimes:jpg,jpeg,png',
            'foto_paspor' => 'nullable|max:2048|mimes:jpg,jpeg,png',
        ], [], [
            'no_paspor' => 'No Paspor',
            'nama' => 'Nama',
            'tempat_lahir' => 'Tempat Lahir',
            'tgl_lahir' => 'Tanggal Lahir',
            'alamat' => 'Alamat',
            'id_provinsi' => 'Provinsi',
            'id_kota' => 'Kota',
            'no_telp' => 'No Telepon',
            'negara_tempat_bekerja' => 'Negara Tempat Bekerja',
            'tahun_bekerja' => 'Mulai bekerja di luar negeri',
            'tgl_kembali' => 'Kembali ke dalam negeri',
            'id_status_kedatangan' => 'Status Kedatangan',
            'masalah' => 'Jenis Masalah',
            'tuntutan' => 'Tuntutan',
            'foto_pmi' => 'Foto PMI',
            'foto_paspor' => 'Foto Paspor',
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
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat,
            'id_provinsi' => $request->id_provinsi,
            'id_kota' => $request->id_kota,
            'no_telp' => $request->no_telp,
            'negara_tempat_bekerja' => $request->negara_tempat_bekerja,
            'tahun_bekerja' => $request->tahun_bekerja,
            'tgl_kembali' => $request->tgl_kembali,
            'id_status_kedatangan' => $request->id_status_kedatangan,
            'masalah' => $request->masalah,
            'tuntutan' => $request->tuntutan,
            'id_user' => auth()->id(),
        ]);

        if ($request->hasFile('foto_pmi')) {
            $path = 'uploads/photo';

            if ($pmi->foto_pmi && basename($pmi->foto_pmi) !== 'blank.jpg') {
                if (File::exists(public_path($path . '/' . basename($pmi->foto_pmi)))) {
                    File::delete(public_path($path . '/' . basename($pmi->foto_pmi)));
                }
            }

            if (!File::isDirectory(public_path($path))) {
                File::makeDirectory(public_path($path), 0777, true, true);
            }

            $foto_pmi = 'pmi_' . time() . '.' . $request->foto_pmi->extension();
            if ($request->foto_pmi->move(public_path($path), $foto_pmi)) {
                $pmi->update([
                    'foto_pmi' => asset('') . $path . '/' . $foto_pmi
                ]);
            };
        }

        if ($request->hasFile('foto_paspor')) {
            $path = 'uploads/paspor';

            if ($pmi->foto_paspor && basename($pmi->foto_paspor) !== 'blank.png') {
                if (File::exists(public_path($path . '/' . basename($pmi->foto_paspor)))) {
                    File::delete(public_path($path . '/' . basename($pmi->foto_paspor)));
                }
            }

            if (!File::isDirectory(public_path($path))) {
                File::makeDirectory(public_path($path), 0777, true, true);
            }

            $foto_paspor = 'paspor_' . time() . '.' . $request->foto_paspor->extension();
            if ($request->foto_paspor->move(public_path($path), $foto_paspor)) {
                $pmi->update([
                    'foto_paspor' => asset('') . $path . '/' . $foto_paspor
                ]);
            };
        }

        return response()->json([
            'message' => 'Update successfully',
            'data' => $pmi,
        ], 200);
    }

    public function destroy($id)
    {
        $pmi = Pmi::findOrFail($id);

        if ($pmi->foto_pmi && basename($pmi->foto_pmi) !== 'blank.jpg') {

            $path = 'uploads/photo';

            if (File::exists(public_path($path . '/' . basename($pmi->foto_pmi)))) {
                File::delete(public_path($path . '/' . basename($pmi->foto_pmi)));
            }
        }

        if ($pmi->foto_paspor && basename($pmi->foto_paspor) !== 'blank.png') {

            $path = 'uploads/paspor';

            if (File::exists(public_path($path . '/' . basename($pmi->foto_paspor)))) {
                File::delete(public_path($path . '/' . basename($pmi->foto_paspor)));
            }
        }

        $pmi->delete();

        return response()->json([
            'message' => 'Delete successfully',
        ], 200);
    }

    public function search(Request $request)
    {
        $data = Pmi::query()->where('nama', 'LIKE', '%' . $request->post('query') . '%')->with(['provinsi', 'kota', 'statusKedatangan', 'user' => function ($query) {
            $query->select('id', 'name');
        }])->orderBy('nama', 'asc')->get();

        return response()->json([
            'message' => 'Search successfully',
            'data' => $data,
        ], 200);
    }
}
