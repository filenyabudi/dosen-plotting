<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class DosenPlottingSheet implements FromView, WithTitle
{
    protected $konsentrasi;
    protected $data;

    public function __construct($konsentrasi, $data)
    {
        $this->konsentrasi = $konsentrasi ?: 'Konsentrasi Kosong';
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.dosen-plotting', [
            'konsentrasi' => $this->konsentrasi,
            'plotting' => $this->data
        ]);
    }

    public function title(): string
    {
        return $this->konsentrasi;
    }
}
