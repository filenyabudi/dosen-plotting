<?php

namespace App\Filament\Resources;

use App\Exports\PlottingExport;
use App\Filament\Resources\DosenPlottingResource\Pages;
use App\Filament\Resources\DosenPlottingResource\RelationManagers;
use App\Models\Dosen;
use App\Models\DosenPlotting;
use App\Models\Matakuliah;
use App\Models\Plotting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Actions\Action;

class DosenPlottingResource extends Resource
{
    protected static ?string $model = DosenPlotting::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Plotting';

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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dosen.nama_lengkap')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plotting.matakuliah.nama_mk')
                    ->label('Mata Kuliah')
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
                    ->label('Export to Excel')
                    ->action(function () {
                        return Excel::download(new PlottingExport, 'plotting.xlsx');
                    })
                    ->icon('heroicon-o-arrow-down-tray'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
