<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;

    protected $table = 'provinsi';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];

    public function kota()
    {
        return $this->hasMany(Kota::class, 'id_provinsi', 'id');
    }

    public function pmi()
    {
        return $this->hasMany(Pmi::class, 'id_provinsi', 'id');
    }
}
