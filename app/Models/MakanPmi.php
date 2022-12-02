<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakanPmi extends Model
{
    use HasFactory;

    protected $table = 'makan_pmi';
    public $timestamps = false;

    protected $guarded = [];
}
