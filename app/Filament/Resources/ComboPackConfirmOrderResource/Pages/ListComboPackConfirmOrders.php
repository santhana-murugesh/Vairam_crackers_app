<?php

namespace App\Filament\Resources\ComboPackConfirmOrderResource\Pages;

use App\Filament\Resources\ComboPackConfirmOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComboPackConfirmOrders extends ListRecords
{
    protected static string $resource = ComboPackConfirmOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
