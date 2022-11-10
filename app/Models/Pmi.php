<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pmi extends Model
{
    use HasFactory, Uuid;

    protected $table = 'pmi';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'id' => 'string',
    ];

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

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function makanPmi()
    {
        return $this->hasMany(MakanPmi::class, 'id_pmi', 'id');
    }
}
