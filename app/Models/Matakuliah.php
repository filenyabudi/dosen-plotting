<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matakuliah extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'kurikulum',
        'konsentrasi_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'konsentrasi_id' => 'integer',
    ];

    public function Konsentrasi(): BelongsTo
    {
        return $this->belongsTo(Konsentrasi::class);
    }

    public function plotting(): HasMany
    {
        return $this->hasMany(Plotting::class);
    }
}
