<?php

namespace App\Filament\Resources\CombopackPaymentsResource\Pages;

use App\Filament\Resources\CombopackPaymentsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCombopackPayments extends EditRecord
{
    protected static string $resource = CombopackPaymentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
