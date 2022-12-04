<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemulangan extends Model
{
    use HasFactory;

    protected $table = 'pemulangan';

    protected $guarded = [];

    public function penyediaJasa()
    {
        return $this->belongsTo(PenyediaJasa::class, 'id_penyedia_jasa', 'id');
    }

    public function jenisPengangkutan()
    {
        return $this->belongsTo(JenisPengangkutan::class, 'id_jenis_pengangkutan', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function pmi()
    {
        return $this->belongsToMany(Pmi::class, 'pemulangan_pmi', 'id_pemulangan', 'id_pmi');
    }
}
