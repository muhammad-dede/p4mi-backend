<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusKedatangan extends Model
{
    use HasFactory;

    protected $table = 'status_kedatangan';
    public $timestamps = false;

    protected $guarded = [];
}
