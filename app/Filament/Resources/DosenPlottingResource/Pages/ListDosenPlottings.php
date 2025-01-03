<?php

namespace App\Filament\Resources\DosenPlottingResource\Pages;

use App\Filament\Resources\DosenPlottingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDosenPlottings extends ListRecords
{
    protected static string $resource = DosenPlottingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
