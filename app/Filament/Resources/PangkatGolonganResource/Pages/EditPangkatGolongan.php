<?php

namespace App\Filament\Resources\PangkatGolonganResource\Pages;

use App\Filament\Resources\PangkatGolonganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPangkatGolongan extends EditRecord
{
    protected static string $resource = PangkatGolonganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
