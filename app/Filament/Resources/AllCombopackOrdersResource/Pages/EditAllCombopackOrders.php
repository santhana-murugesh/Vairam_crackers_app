<?php

namespace App\Filament\Resources\AllCombopackOrdersResource\Pages;

use App\Filament\Resources\AllCombopackOrdersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAllCombopackOrders extends EditRecord
{
    protected static string $resource = AllCombopackOrdersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
