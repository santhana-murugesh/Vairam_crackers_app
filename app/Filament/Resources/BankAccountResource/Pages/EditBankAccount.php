<?php

namespace App\Filament\Resources\BankAccountResource\Pages;

use App\Filament\Resources\BankAccountResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBankAccount extends EditRecord
{
    protected static string $resource = BankAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
