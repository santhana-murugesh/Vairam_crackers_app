<?php

namespace App\Filament\Resources\CombopackOrderResource\Pages;

use App\Filament\Resources\CombopackOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCombopackOrders extends ListRecords
{
    protected static string $resource = CombopackOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
