<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPemulangan extends Model
{
    use HasFactory;

    protected $table = 'status_pemulangan';
    public $timestamps = false;

    protected $guarded = [];
}
