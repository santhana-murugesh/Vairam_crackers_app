<?php

namespace App\Filament\Resources\AllCombopackOrdersResource\Pages;

use App\Filament\Resources\AllCombopackOrdersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAllCombopackOrders extends ListRecords
{
    protected static string $resource = AllCombopackOrdersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
