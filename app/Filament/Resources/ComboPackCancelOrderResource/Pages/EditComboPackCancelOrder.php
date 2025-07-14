<?php

namespace App\Filament\Resources\ComboPackCancelOrderResource\Pages;

use App\Filament\Resources\ComboPackCancelOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComboPackCancelOrder extends EditRecord
{
    protected static string $resource = ComboPackCancelOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
