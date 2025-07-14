<?php

namespace App\Filament\Resources\ComboPackRefundResource\Pages;

use App\Filament\Resources\ComboPackRefundResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComboPackRefund extends EditRecord
{
    protected static string $resource = ComboPackRefundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
