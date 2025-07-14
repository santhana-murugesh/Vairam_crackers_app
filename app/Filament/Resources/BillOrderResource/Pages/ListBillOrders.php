<?php

namespace App\Filament\Resources\BillOrderResource\Pages;

use App\Filament\Resources\BillOrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBillOrders extends ListRecords
{
    protected static string $resource = BillOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
          
        ];
    }
}
