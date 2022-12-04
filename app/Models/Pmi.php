<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pmi extends Model
{
    use HasFactory;

    protected $table = 'pmi';

    protected $guarded = [];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi', 'id');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id');
    }

    public function statusKedatangan()
    {
        return $this->belongsTo(StatusKedatangan::class, 'id_status_kedatangan', 'id');
    }

    public function statusPemulangan()
    {
        return $this->belongsTo(StatusPemulangan::class, 'id_status_pemulangan', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function makan()
    {
        return $this->belongsToMany(Makan::class, 'makan_pmi', 'id_makan', 'id_pmi');
    }

    public function pemulangan()
    {
        return $this->belongsToMany(Pemulangan::class, 'pemulangan_pmi', 'id_pemulangan', 'id_pmi');
    }
}
