<?php

namespace App\Filament\Resources\ComboPackPackingResource\Pages;

use App\Filament\Resources\ComboPackPackingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComboPackPackings extends ListRecords
{
    protected static string $resource = ComboPackPackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
