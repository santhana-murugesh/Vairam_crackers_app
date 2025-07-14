<?php

namespace App\Filament\Resources\ComboPackPackingResource\Pages;

use App\Filament\Resources\ComboPackPackingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComboPackPacking extends EditRecord
{
    protected static string $resource = ComboPackPackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
