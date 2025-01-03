<?php

namespace App\Filament\Resources\KonsentrasiResource\Pages;

use App\Filament\Resources\KonsentrasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKonsentrasi extends EditRecord
{
    protected static string $resource = KonsentrasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
