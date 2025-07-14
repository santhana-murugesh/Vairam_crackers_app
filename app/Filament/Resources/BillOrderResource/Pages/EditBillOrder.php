<?php

namespace App\Filament\Resources\BillOrderResource\Pages;

use App\Filament\Resources\BillOrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBillOrder extends EditRecord
{
    protected static string $resource = BillOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\DeleteAction::make(),
            // Actions\Action::make('print')->url(route('admin.orders.print', $this->record->id )),
            Actions\Action::make('download Pdf')->url(route('admin.bill-orders.download', $this->record->id )),
            // Actions\Action::make('print')->url(route('admin.bill-orders.print', $this->record->id )),
        ];
    }
}
