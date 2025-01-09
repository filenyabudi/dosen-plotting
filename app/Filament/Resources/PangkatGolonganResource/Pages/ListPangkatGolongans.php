<?php

namespace App\Filament\Resources\PangkatGolonganResource\Pages;

use App\Filament\Resources\PangkatGolonganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPangkatGolongans extends ListRecords
{
    protected static string $resource = PangkatGolonganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
