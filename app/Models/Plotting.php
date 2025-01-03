<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plotting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'matakuliah_id',
        'peserta',
        'jumlah_kelas',
        'koordinator_id',
        'pembina_id',
        'tahun',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'dosen_id' => 'integer',
        'matakuliah_id' => 'integer',
    ];

    public function matakuliah(): BelongsTo
    {
        return $this->belongsTo(Matakuliah::class);
    }

    public function koordinator(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function pembina(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}
