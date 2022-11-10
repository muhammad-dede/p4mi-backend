<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakanPmi extends Model
{
    use HasFactory, Uuid;

    protected $table = 'makan_pmi';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'id' => 'string',
    ];

    public function makan()
    {
        return $this->belongsTo(Makan::class, 'id_makan', 'id');
    }

    public function pmi()
    {
        return $this->belongsTo(Pmi::class, 'id_pmi', 'id');
    }
}
