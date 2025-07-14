<?php

namespace App\Filament\Resources\AllOrdersResource\Pages;

use App\Filament\Resources\AllOrdersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAllOrders extends EditRecord
{
    protected static string $resource = AllOrdersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\RestoreAction::make(),
            // Actions\ReplicateAction::make()
            // ->action(function (Model $order) {

            //     $orderToClone = Order::with('customer', 'address', 'items')->find($order->id);

            //     if ($orderToClone) {
            //         $cloneOrder = $orderToClone->replicate();

            //         $cloneOrder->created_at = now();
            //         $cloneOrder->save();

            //         $cloneCustomer = $orderToClone->customer->replicate();
            //         $cloneCustomer->save();

            //         $cloneAddress = $orderToClone->address->replicate();
            //         $cloneAddress->save();

            //         $cloneItems = collect();

            //         foreach ($orderToClone->items as $itemToClone) {
            //             $clonedItem = $itemToClone->replicate();
            //             $clonedItem->save();
            //             $cloneItems->push($clonedItem);
            //         }

            //         $cloneOrder->customer()->associate($cloneCustomer);
            //         $cloneOrder->address()->associate($cloneAddress);
            //         $cloneOrder->save();

            //         $cloneOrder->items()->saveMany($cloneItems);

            //         return $cloneOrder->id;
            //     }
            //     return null;

            // })
        ];
    
    }
}
