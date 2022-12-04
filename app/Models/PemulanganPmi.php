<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemulanganPmi extends Model
{
    use HasFactory;

    protected $table = 'pemulangan_pmi';
    public $timestamps = false;

    protected $guarded = [];
}
