<?php

namespace App\Filament\Resources\DeletedBankAccountResource\Pages;

use App\Filament\Resources\DeletedBankAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeletedBankAccounts extends ListRecords
{
    protected static string $resource = DeletedBankAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return "Deleted BankAccounts";
    }
}
