<?php

namespace App\Exports;

use App\Models\DosenPlotting;
use App\Models\TahunAkademik;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class GroupDosenPlottingExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $data = DosenPlotting::select('matakuliahs.nama_mk', 'matakuliahs.semester', 'matakuliahs.sks', 'plottings.peserta', 'plottings.jumlah_kelas', 'dosen_plottings.kelas', 'dosens.nama_lengkap as dosen_pengajar', 'pembina.nama_lengkap as pembina', 'koordinator.nama_lengkap as koordinator', 'dosen_plottings.jenis', 'pangkat_golongans.nama_pangkat', 'jabatans.nama_jabatan', 'konsentrasis.nama_konsentrasi')
            ->join('plottings', 'dosen_plottings.plotting_id', '=', 'plottings.id')
            ->join('tahun_akademiks', 'plottings.tahun', '=', 'tahun_akademiks.nama_singkat')
            ->join('matakuliahs', 'plottings.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'dosen_plottings.dosen_id', '=', 'dosens.id')
            ->join('pangkat_golongans', 'dosens.pangkat_golongan_id', '=', 'pangkat_golongans.id')
            ->join('jabatans', 'dosens.jabatan_id', '=', 'jabatans.id')
            ->leftJoin('konsentrasis', 'dosens.konsentrasi_id', '=', 'konsentrasis.id')
            ->leftJoin('dosens as pembina', 'plottings.pembina_id', '=', 'pembina.id')
            ->leftJoin('dosens as koordinator', 'plottings.koordinator_id', '=', 'koordinator.id')
            ->where('tahun_akademiks.aktif', '1')
            ->get();

        $tahun_akademik = TahunAkademik::where('aktif', '1')->first();

        $groupedData = $data->groupBy('nama_konsentrasi');
        $sheets = [];

        foreach ($groupedData as $konsentrasi => $data) {
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

                $keterangan = null;
                if ($value->dosen_pengajar == $value->koordinator) {
                    $keterangan = "Koordinator";
                } else if ($value->dosen_pengajar == $value->pembina) {
                    $keterangan = "Pembina";
                }

                $temp[$dosen_pengajar]['nama_mk'][] = [
                    'nama_mk' => $value->nama_mk,
                    'sks' => $value->sks,
                    'konsentrasi' => $value->nama_konsentrasi,
                    'jumlah_kelas' => count(explode(',', $value->kelas)),
                    'sks_kelas' => count(explode(',', $value->kelas)) * $value->sks,
                    'keterangan' => $keterangan,
                ];

                $temp[$dosen_pengajar]['total_sks_kelas'] = isset($temp[$dosen_pengajar]['total_sks_kelas']) ? $temp[$dosen_pengajar]['total_sks_kelas'] : 0;

                $temp[$dosen_pengajar]['total_sks_kelas'] += count(explode(',', $value->kelas)) * $value->sks;

                $temp[$dosen_pengajar]['total_sks'] += $value->sks;
            }

            $temp = array_values($temp);

            $sheets[] = new DosenPlottingSheet($konsentrasi, $temp, $tahun_akademik);
        }

        return $sheets;
    }
}
