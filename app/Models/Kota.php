<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;

    protected $table = 'kota';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi', 'id');
    }

    public function pmi()
    {
        return $this->hasMany(Pmi::class, 'id_kota', 'id');
    }
}
