<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Makan extends Model
{
    use HasFactory;

    protected $table = 'makan';

    protected $guarded = [];

    public function penyediaJasa()
    {
        return $this->belongsTo(PenyediaJasa::class, 'id_penyedia_jasa', 'id');
    }

    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class, 'id_jenis_barang', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function makanDetail()
    {
        return $this->hasMany(MakanDetail::class, 'id_makan', 'id');
    }
}
