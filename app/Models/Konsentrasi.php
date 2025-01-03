<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsentrasi extends Model
{
    //
    protected $fillable = [
        'nama_konsentrasi',
    ];

    protected $casts = [
        'id' => 'integer',
    ];
}
