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
        $totalMataKuliah = Matakuliah::count();
        $totalPlotting = Plotting::count();
        $totalDosenPlotting = DosenPlotting::count();

        return [
            Stat::make('Total Dosen', $totalDosen),
            Stat::make('Total Mata Kuliah', $totalMataKuliah),
            Stat::make('Total Plotting', $totalPlotting),
            Stat::make('Total Dosen Plotting', $totalDosenPlotting),
        ];
    }
}
