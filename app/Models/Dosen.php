<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dosen extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nidn',
        'nama_lengkap',
        'pangkat_golongan_id',
        'jabatan_id',
        'kosentrasi_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function PangkatGolongan(): BelongsTo
    {
        return $this->belongsTo(PangkatGolongan::class);
    }

    public function Jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function Konsentrasi(): BelongsTo
    {
        return $this->belongsTo(Konsentrasi::class);
    }
}
