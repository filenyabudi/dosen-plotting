<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class DosenPlottingSheet implements FromView, WithTitle
{
    protected $konsentrasi;
    protected $data;
    protected $tahun_akademik;

    public function __construct($konsentrasi, $data, $tahun_akademik)
    {
        $this->konsentrasi = $konsentrasi ?: 'Konsentrasi Kosong';
        $this->data = $data;
        $this->tahun_akademik = $tahun_akademik;
    }

    public function view(): View
    {
        return view('exports.dosen-plotting', [
            'konsentrasi' => $this->konsentrasi,
            'plotting' => $this->data,
            'tahun_akademik' => $this->tahun_akademik,
        ]);
    }

    public function title(): string
    {
        return $this->konsentrasi;
    }
}
