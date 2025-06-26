<?php

namespace App\Filament\Widgets;

use App\Models\Plotting;
use Filament\Widgets\ChartWidget;

class WidgetPlottingChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Pesrta Plotting';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // $pesertaPlotting = Plotting::with('matakuliah')
        //     ->get();

        $pesertaPlotting = Plotting::with('matakuliah')
            ->join('tahun_akademiks', function ($join) {
                $join->on('plottings.tahun', '=', 'tahun_akademiks.nama_singkat')
                    ->where('tahun_akademiks.aktif', '1');
            })
            ->select('plottings.*') // Ensure all plotting columns are selected
            ->get();

        $arrData = [];
        foreach ($pesertaPlotting as $keyPlotting => $valuePlotting) {
            $row = [];
            $row['peserta'] = $valuePlotting->peserta;
            $row['nama_mk'] = $valuePlotting->matakuliah['nama_mk'];
            $arrData[] = $row;
        }
        $data = [];
        $labels = [];

        foreach ($arrData as $dataPoint) {
            $data[] = $dataPoint['peserta'];
            $labels[] = $dataPoint['nama_mk'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Peserta',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
