<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Filament\Notifications\Notification;

class DosenPlotting extends Model
{
    //
    protected $fillable = [
        'dosen_id',
        'kelas',
        'jenis',
        'plotting_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'plotting_id' => 'integer',
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function plotting(): BelongsTo
    {
        return $this->belongsTo(Plotting::class);
    }
}
