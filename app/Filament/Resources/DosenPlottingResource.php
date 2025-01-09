<?php

namespace App\Filament\Resources;

use App\Exports\DosenPlottingExport;
use App\Exports\GroupDosenPlottingExport;
use App\Exports\PlottingExport;
use App\Filament\Resources\DosenPlottingResource\Pages;
use App\Models\DosenPlotting;
use App\Models\Plotting;
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
            ->columns([
                Tables\Columns\TextColumn::make('dosen.nama_lengkap')
                    ->numeric()
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
