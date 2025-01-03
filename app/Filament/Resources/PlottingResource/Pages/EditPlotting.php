<?php

namespace App\Filament\Resources\PlottingResource\Pages;

use App\Filament\Resources\PlottingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlotting extends EditRecord
{
    protected static string $resource = PlottingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
