<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $fillable = [
        'nama_singkat',
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'aktif',
        'keterangan',
    ];

    protected $casts = [
        'id' => 'integer',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'aktif' => 'boolean',
    ];
}
