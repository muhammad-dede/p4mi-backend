<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPengangkutan extends Model
{
    use HasFactory;

    protected $table = 'jenis_pengangkutan';
    public $timestamps = false;

    protected $guarded = [];
}
