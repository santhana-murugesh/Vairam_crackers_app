<?php

namespace App\Filament\Resources\ComboPackConfirmOrderResource\Pages;

use App\Filament\Resources\ComboPackConfirmOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComboPackConfirmOrder extends EditRecord
{
    protected static string $resource = ComboPackConfirmOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
