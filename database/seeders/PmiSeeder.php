<?php

namespace Database\Seeders;

use App\Models\Pmi;
use App\Models\User;
use Illuminate\Database\Seeder;

class PmiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        $pmis = [
            [
                'no_paspor' => '12345678910',
                'nama' => 'Hambali Syamsudin',
                'tempat_lahir' => 'Keragilan',
                'tanggal_lahir' => '1996-12-12',
                'alamat' => 'Keragilan North West',
                'id_provinsi' => 13,
                'id_kota' => 12,
                'telepon' => '08123456789',
                'negara_tempat_bekerja' => 'Zimwabwe',
                'tahun_bekerja' => '2017',
                'tanggal_kembali' => '2022-10-10',
                'id_status_kedatangan' => 3,
                'id_status_pemulangan' => 1,
                'masalah' => 'Ini adalah jenis masalah',
                'tuntutan' => 'Ini adalah tuntutan',
                'photo_pmi' => asset('uploads/photos/user_blank.jpg'),
                'photo_paspor' => asset('uploads/photos/paspor_blank.png'),
                'id_user' => $user->id,
            ],
            [
                'no_paspor' => '09876543212',
                'nama' => 'Riwan Febrianto',
                'tempat_lahir' => 'Serang',
                'tanggal_lahir' => "1999-12-12",
                'alamat' => 'Taman Ciruas Percai Blok B3 Serang Banten',
                'id_provinsi' => 11,
                'id_kota' => 12,
                'telepon' => '081209876543',
                'negara_tempat_bekerja' => 'Jepang',
                'tahun_bekerja' => '2019',
                'tanggal_kembali' => '2022-10-10',
                'id_status_kedatangan' => 3,
                'id_status_pemulangan' => 1,
                'masalah' => 'Ini adalah jenis masalah',
                'tuntutan' => 'Ini adalah tuntutan',
                'photo_pmi' => asset('uploads/photos/user_blank.jpg'),
                'photo_paspor' => asset('uploads/photos/paspor_blank.png'),
                'id_user' => $user->id,
            ],
            [
                'no_paspor' => '09876542323',
                'nama' => 'Syaefi Buchori',
                'tempat_lahir' => 'Serang',
                'tanggal_lahir' => "1998-12-11",
                'alamat' => 'Pontang',
                'id_provinsi' => 12,
                'id_kota' => 12,
                'telepon' => '081209876545',
                'negara_tempat_bekerja' => 'Malaysia',
                'tahun_bekerja' => '2020',
                'tanggal_kembali' => '2022-10-10',
                'id_status_kedatangan' => 3,
                'id_status_pemulangan' => 1,
                'masalah' => 'Ini adalah jenis masalah',
                'tuntutan' => 'Ini adalah tuntutan',
                'photo_pmi' => asset('uploads/photos/user_blank.jpg'),
                'photo_paspor' => asset('uploads/photos/paspor_blank.png'),
                'id_user' => $user->id,
            ],
        ];

        foreach ($pmis as $pmi) {
            Pmi::create($pmi);
        }
    }
}
