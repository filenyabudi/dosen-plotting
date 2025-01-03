<?php

namespace App\Filament\Resources\DosenPlottingResource\Pages;

use App\Filament\Resources\DosenPlottingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDosenPlotting extends EditRecord
{
    protected static string $resource = DosenPlottingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
