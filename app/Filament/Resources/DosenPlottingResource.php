<?php

namespace App\Filament\Resources;

use App\Exports\DosenPlottingExport;
use App\Exports\GroupDosenPlottingExport;
use App\Exports\PlottingExport;
use App\Filament\Resources\DosenPlottingResource\Pages;
use App\Models\DosenPlotting;
use App\Models\Plotting;
use App\Models\TahunAkademik;
use Barryvdh\DomPDF\Facade\Pdf;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Actions\Action;

class DosenPlottingResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;

    protected static ?string $model = DosenPlotting::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Plotting';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('dosen_id')
                    ->relationship('dosen', 'nama_lengkap')
                    ->label('Nama Dosen')
                    ->required()
                    ->searchable(),
                Forms\Components\TagsInput::make('kelas')
                    ->required()
                    ->separator(","),
                Select::make('jenis')
                    ->options([
                        'Dosen Pengajar' => 'Dosen Pengajar',
                        'Asisten Dosen' => 'Asisten Dosen',
                    ])
                    ->required(),
                Select::make('plotting_id')
                    ->label('Nama Mata Kuliah')
                    ->options(
                        Plotting::with('matakuliah')
                            ->get()
                            ->pluck('matakuliah.nama_mk', 'id')
                    )
                    ->searchable()
                    ->required()
                    ->rule(static function (Forms\Get $get, Forms\Components\Component $component): Closure {
                        return static function (string $attribute, $value, Closure $fail) use ($get, $component) {
                            $kelas = implode(',', $get('kelas'));
                            $dosenPlotting = DosenPlotting::where('dosen_id', $get('dosen_id'))
                                ->where('kelas', $kelas)
                                ->where('jenis', $get('jenis'))
                                ->where('plotting_id', $value)
                                ->first();

                            if ($dosenPlotting) {
                                $fail('Dosen dan matakuliah sudah dibuat');
                            }
                        };
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                DosenPlotting::query()
                    ->whereHas('plotting', function ($query) {
                        $query->join('tahun_akademiks', function ($join) {
                            $join->on('plottings.tahun', '=', 'tahun_akademiks.nama_singkat')
                                ->where('tahun_akademiks.aktif', '1');
                        })
                            ->select('plottings.*');
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('dosen.nama_lengkap')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis'),
                Tables\Columns\TextColumn::make('kelas'),
                Tables\Columns\TextColumn::make('plotting.matakuliah.nama_mk')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Draft Mata Kuliah')
                    ->action(function () {
                        return Excel::download(new PlottingExport, 'draft-mata-kuliah.xlsx');
                    })
                    ->icon('heroicon-o-arrow-down-tray'),
                Action::make('export2')
                    ->label('Draft Total Dosen')
                    ->action(function () {
                        return Excel::download(new DosenPlottingExport, 'draft-total-dosen.xlsx');
                    })
                    ->icon('heroicon-o-arrow-down-tray'),
                Action::make('export3')
                    ->label('Draft Dosen')
                    ->action(function () {
                        return Excel::download(new GroupDosenPlottingExport, 'draft-dosen.xlsx');
                    })
                    ->icon('heroicon-o-arrow-down-tray'),
                Action::make('cetak_dosen_plotting')
                    ->label('Cetak Petikan Dosen')
                    ->icon('heroicon-o-printer')
                    ->form([
                        Forms\Components\TextInput::make('nomor_sk')
                            ->label('Nomor SK')
                            ->required()
                            ->placeholder('Masukkan nomor SK'),
                        Forms\Components\DatePicker::make('tanggal_sk')
                            ->label('Tanggal SK')
                            ->required()
                            ->default(now())
                            ->format('Y-m-d'),
                    ])
                    ->action(function (array $data) {
                        $dosenPlotting = DosenPlotting::select('matakuliahs.nama_mk', 'matakuliahs.semester', 'matakuliahs.sks', 'plottings.peserta', 'plottings.jumlah_kelas', 'dosen_plottings.kelas', 'dosens.nama_lengkap as dosen_pengajar', 'pembina.nama_lengkap as pembina', 'koordinator.nama_lengkap as koordinator', 'dosen_plottings.jenis', 'pangkat_golongans.nama_pangkat', 'jabatans.nama_jabatan', 'konsentrasis.nama_konsentrasi')
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

                        $temp = [];
                        foreach ($dosenPlotting as $key => $value) {
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

                        $pdfData = [
                            'plotting' => $temp,
                            'tahun_akademik' => $tahun_akademik,
                            'nomor_sk' => $data['nomor_sk'],
                            'tanggal' => $data['tanggal_sk'],
                        ];

                        $pdf = Pdf::loadView('pdf.petikan-dosen', $pdfData)
                            ->setPaper('a4', 'portrait');
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'dosen-plotting.pdf');
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDosenPlottings::route('/'),
            'create' => Pages\CreateDosenPlotting::route('/create'),
            'edit' => Pages\EditDosenPlotting::route('/{record}/edit'),
        ];
    }
}
