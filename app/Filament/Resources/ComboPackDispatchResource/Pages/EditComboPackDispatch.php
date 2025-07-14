<?php

namespace App\Filament\Resources\ComboPackDispatchResource\Pages;

use App\Filament\Resources\ComboPackDispatchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComboPackDispatch extends EditRecord
{
    protected static string $resource = ComboPackDispatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
