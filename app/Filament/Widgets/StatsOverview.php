<?php

namespace App\Filament\Widgets;

use App\Models\Dosen;
use App\Models\DosenPlotting;
use App\Models\Matakuliah;
use App\Models\Plotting;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalDosen = Dosen::count();

        $totalMataKuliah = Matakuliah::join('plottings', 'matakuliahs.id', '=', 'plottings.matakuliah_id')
            ->join('tahun_akademiks', 'plottings.tahun', '=', 'tahun_akademiks.nama_singkat')
            ->where('tahun_akademiks.aktif', 1)
            ->count();

        $totalPlotting = Plotting::join('tahun_akademiks', function ($join) {
            $join->on('plottings.tahun', '=', 'tahun_akademiks.nama_singkat')
                ->where('tahun_akademiks.aktif', 1);
        })->count();

        $totalDosenPlotting = DosenPlotting::join('plottings', 'dosen_plottings.plotting_id', '=', 'plottings.id')
            ->join('tahun_akademiks', 'plottings.tahun', '=', 'tahun_akademiks.nama_singkat')
            ->where('tahun_akademiks.aktif', 1)
            ->count();

        return [
            Stat::make('Total Dosen', $totalDosen),
            Stat::make('Total Mata Kuliah', $totalMataKuliah),
            Stat::make('Total Plotting', $totalPlotting),
            Stat::make('Total Dosen Plotting', $totalDosenPlotting),
        ];
    }
}
