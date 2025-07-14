<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Redirect;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\DeleteAction::make(),
            Actions\Action::make('download Pdf')->url(route('admin.orders.download', $this->record->id))->openUrlInNewTab(),
            // Actions\ReplicateAction::make('Replicate')
            //     ->action(function (Model $order) {

            //         $orderToClone = Order::with('customer', 'address', 'items')->find($order->id);

            //         if ($orderToClone) {
            //             $cloneOrder = $orderToClone->replicate();

            //             $cloneOrder->created_at = now();
            //             $cloneOrder->save();

            //             $cloneCustomer = $orderToClone->customer->replicate();
            //             $cloneCustomer->save();

            //             $cloneAddress = $orderToClone->address->replicate();
            //             $cloneAddress->save();

            //             $cloneItems = collect();

            //             foreach ($orderToClone->items as $itemToClone) {
            //                 $clonedItem = $itemToClone->replicate();
            //                 $clonedItem->save();
            //                 $cloneItems->push($clonedItem);
            //             }

            //             $cloneOrder->customer()->associate($cloneCustomer);
            //             $cloneOrder->address()->associate($cloneAddress);
            //             $cloneOrder->save();

            //             $cloneOrder->items()->saveMany($cloneItems);

            //             return $cloneOrder->id;
            //         }
            //         return null;
            //     }),
            Actions\Action::make('Refund')
                ->action(function (array $data, Order $order) {
                     $order->update(['status' => 'refund']);
                     return Redirect::to('admin/orders');
                })->icon('heroicon-o-arrow-uturn-right'),
                
            Actions\Action::make('print')->url(route('admin.orders.print', $this->record->id )),
            // Actions\Action::make('download Pdf')->url(route('admin.orders.download', $this->record->id )),
        ];
    }
}
