<?php

namespace App\Filament\Resources\DeletedBankAccountResource\Pages;

use App\Filament\Resources\DeletedBankAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDeletedBankAccount extends CreateRecord
{
    protected static string $resource = DeletedBankAccountResource::class;
}
