<?php

namespace App\Filament\Resources\DeletedBankAccountResource\Pages;

use App\Filament\Resources\DeletedBankAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeletedBankAccount extends EditRecord
{
    protected static string $resource = DeletedBankAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
