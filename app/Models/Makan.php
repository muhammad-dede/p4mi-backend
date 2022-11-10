<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Makan extends Model
{
    use HasFactory, Uuid;

    protected $table = 'makan';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'id' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function makanPmi()
    {
        return $this->hasMany(MakanPmi::class, 'id_makan', 'id');
    }
}
