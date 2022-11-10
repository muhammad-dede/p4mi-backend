<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusKedatangan extends Model
{
    use HasFactory;

    protected $table = 'status_kedatangan';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];

    public function pmi()
    {
        return $this->hasMany(Pmi::class, 'id_status_kedatangan', 'id');
    }
}
