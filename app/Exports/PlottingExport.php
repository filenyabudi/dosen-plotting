<?php

namespace App\Exports;

use App\Models\DosenPlotting;
use App\Models\Plotting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class PlottingExport implements FromView
{
    public function view(): View
    {

        $data = DosenPlotting::select('matakuliahs.nama_mk', 'matakuliahs.semester', 'matakuliahs.sks', 'plottings.peserta', 'plottings.jumlah_kelas', 'dosen_plottings.kelas', 'dosens.nama_lengkap as dosen_pengajar', 'pembina.nama_lengkap as pembina', 'koordinator.nama_lengkap as koordinator')
            ->join('plottings', 'dosen_plottings.plotting_id', '=', 'plottings.id')
            ->join('matakuliahs', 'plottings.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'dosen_plottings.dosen_id', '=', 'dosens.id')
            ->join('dosens as pembina', 'plottings.pembina_id', '=', 'pembina.id')
            ->join('dosens as koordinator', 'plottings.koordinator_id', '=', 'koordinator.id')
            ->get();


        $temp = [];
        foreach ($data as $key => $value) {
            $nama_mk = $value->nama_mk;
            if (!isset($temp[$nama_mk])) {
                $temp[$nama_mk] = [
                    'nama_mk' => $value->nama_mk,
                    'smt' => $value->semester,
                    'sks' => $value->sks,
                    'peserta' => $value->peserta,
                    'dosen' => [],
                    'koordinator' => $value->koordinator,
                    'pembina' =>  $value->pembina,
                    'total_kelas' => $temp[$nama_mk]['total_jumlah_kelas'] = isset($temp[$nama_mk]['total_jumlah_kelas']) ? $temp[$nama_mk]['total_jumlah_kelas'] + $value->jumlah_kelas : $value->jumlah_kelas
                ];
            }
            $temp[$nama_mk]['dosen'][] = [
                'kelas' => $value->kelas,
                'nama_dosen' => $value->dosen_pengajar,
                'jumlah_kelas' => count(explode(',', $value->kelas)),
            ];
        }

        $temp = array_values($temp);

        return view('exports.plotting', [
            'plotting' => $temp
        ]);
    }
}
