<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakanDetail extends Model
{
    use HasFactory;

    protected $table = 'makan_detail';

    protected $guarded = [];

    public function makan()
    {
        return $this->belongsTo(Makan::class, 'id_makan', 'id');
    }

    public function pmi()
    {
        return $this->belongsTo(Pmi::class, 'id_pmi', 'id');
    }
}
