<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Exports\PlottingExport;
use App\Filament\Resources\PlottingResource\Pages;
use App\Filament\Resources\PlottingResource\RelationManagers;
use App\Models\Plotting;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;


class PlottingResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;

    protected static ?string $model = Plotting::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

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
                Select::make('matakuliah_id')
                    ->relationship('matakuliah', 'nama_mk')
                    ->label('Mata Kuliah')
                    ->required()
                    ->searchable()
                    ->rule(static function (Forms\Get $get, Forms\Components\Component $component): Closure {
                        return static function (string $attribute, $value, Closure $fail) use ($get, $component) {
                            $plotting = Plotting::where('peserta', $get('peserta'))
                                ->where('jumlah_kelas', $get('jumlah_kelas'))
                                ->where('koordinator_id', $get('koordinator_id'))
                                ->where('pembina_id', $get('pembina_id'))
                                ->where('tahun', $get('tahun'))
                                ->where('matakuliah_id', $value)
                                ->first();

                            if ($plotting) {
                                $fail('Plotting mata kuliah sudah dibuat');
                            }
                        };
                    }),
                Forms\Components\TextInput::make('peserta')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jumlah_kelas')
                    ->required()
                    ->numeric(),
                Select::make('koordinator_id')
                    ->relationship('koordinator', 'nama_lengkap')
                    ->label('Nama Koordinator')
                    ->searchable(),
                Select::make('pembina_id')
                    ->relationship('pembina', 'nama_lengkap')
                    ->label('Nama Pembina')
                    ->searchable(),
                Forms\Components\TextInput::make('tahun')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('matakuliah.nama_mk')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('matakuliah.semester')
                    ->label('Semester'),
                Tables\Columns\TextColumn::make('matakuliah.sks')
                    ->label('SKS'),
                Tables\Columns\TextColumn::make('peserta'),
                Tables\Columns\TextColumn::make('jumlah_kelas'),
                Tables\Columns\TextColumn::make('koordinator.nama_lengkap')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pembina.nama_lengkap')
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

    protected function getTableHeaderActions(): array
    {
        return [];
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
            'index' => Pages\ListPlottings::route('/'),
            'create' => Pages\CreatePlotting::route('/create'),
            'edit' => Pages\EditPlotting::route('/{record}/edit'),
        ];
    }
}
