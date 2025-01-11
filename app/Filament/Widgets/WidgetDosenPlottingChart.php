<?php

namespace App\Filament\Widgets;

use App\Models\DosenPlotting;
use Filament\Widgets\ChartWidget;

class WidgetDosenPlottingChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah SKS Dosen';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = DosenPlotting::select('matakuliahs.nama_mk', 'matakuliahs.semester', 'matakuliahs.sks', 'plottings.peserta', 'plottings.jumlah_kelas', 'dosen_plottings.kelas', 'dosens.nama_lengkap as dosen_pengajar', 'pembina.nama_lengkap as pembina', 'koordinator.nama_lengkap as koordinator', 'dosen_plottings.jenis', 'pangkat_golongans.nama_pangkat', 'jabatans.nama_jabatan', 'konsentrasis.nama_konsentrasi')
            ->join('plottings', 'dosen_plottings.plotting_id', '=', 'plottings.id')
            ->join('matakuliahs', 'plottings.matakuliah_id', '=', 'matakuliahs.id')
            ->leftJoin('konsentrasis', 'matakuliahs.konsentrasi_id', '=', 'konsentrasis.id')
            ->join('dosens', 'dosen_plottings.dosen_id', '=', 'dosens.id')
            ->join('pangkat_golongans', 'dosens.pangkat_golongan_id', '=', 'pangkat_golongans.id')
            ->join('jabatans', 'dosens.jabatan_id', '=', 'jabatans.id')
            ->leftJoin('dosens as pembina', 'plottings.pembina_id', '=', 'pembina.id')
            ->join('dosens as koordinator', 'plottings.koordinator_id', '=', 'koordinator.id')
            ->get();

        $temp = [];
        foreach ($data as $key => $value) {
            $dosen_pengajar = $value->dosen_pengajar;
            if (!isset($temp[$dosen_pengajar])) {
                $temp[$dosen_pengajar] = [
                    'dosen_pengajar' => $value->dosen_pengajar,
                    'pangkat_golongan' => $value->nama_pangkat,
                    'jabatan' => $value->nama_jabatan,
                    'smt' => $value->semester,
                    'sks' => $value->sks,
                    'total_sks' => 0,
                    'nama_mk' => [],
                ];
            }

            $temp[$dosen_pengajar]['nama_mk'][] = [
                'nama_mk' => $value->nama_mk,
                'sks' => $value->sks,
                'konsentrasi' => $value->nama_konsentrasi,
                'jumlah_kelas' => count(explode(',', $value->kelas)),
                'sks_kelas' => count(explode(',', $value->kelas)) * $value->sks,
            ];

            $temp[$dosen_pengajar]['total_sks_kelas'] = isset($temp[$dosen_pengajar]['total_sks_kelas']) ? $temp[$dosen_pengajar]['total_sks_kelas'] : 0;

            $temp[$dosen_pengajar]['total_sks_kelas'] += count(explode(',', $value->kelas)) * $value->sks;

            $temp[$dosen_pengajar]['total_sks'] += $value->sks;
        }

        $temp = array_values($temp);

        $data = [];
        $labels = [];

        foreach ($temp as $dataPoint) {
            $data[] = $dataPoint['total_sks_kelas'];
            $labels[] = $dataPoint['dosen_pengajar'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'SKS',
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
