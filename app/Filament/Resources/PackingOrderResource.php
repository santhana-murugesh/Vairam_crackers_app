<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackingOrderResource\Pages;
use App\Filament\Resources\PackingOrderResource\RelationManagers;
use App\Models\Order;
use App\Models\BankAccount;
use App\Models\Payment;
use App\Models\Orderstatus;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Dispatch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ButtonAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;



class PackingOrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Orders';

    protected static ?string $navigationLabel = 'Packing Orders';

    protected static ?int $navigationSort = 3;

   
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '=', 'packing')->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                ->label('Order ID')
                ->required()
                ->maxLength(255),

            Select::make('customer_id')
                ->label('Customer')
                ->options(Customer::all()->pluck('name', 'id'))
                ->required()
                ->searchable(),

            Forms\Components\TextInput::make('net_total')
                ->required()
                ->maxLength(255),

            Select::make('address_id')
                ->label('shipping address')
                ->options(Address::all()->pluck('address', 'id'))
                ->searchable(),

            Select::make('address_id')
                ->label(' city')
                ->options(Address::all()->pluck('city_town', 'id'))
                ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

        ->query(function () {

            $query = Order::query();

            return $query->whereNot('status', 'dispatched')->latest('created_at');
        })

        ->query(function () {

            $query = Order::query();

            return $query->where('status', 'packing')->latest('created_at');
        })


            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Order ID')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Ordered date')->searchable()->toggleable()->date(),
                Tables\Columns\TextColumn::make('customer.name')->label('Customer name')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('customer.mobile_number')->label('Mobile number')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('address.city_town')->label('City/Town')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('net_total')->searchable()->toggleable(),
              
                Tables\Columns\TextColumn::make('status')->searchable(['customer', 'orderstatus'])->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('status')->searchable('status')->searchable()->toggleable()->sortable(),

                    Tables\Columns\TextColumn::make('created_at')->label('Ordered date')->searchable()->toggleable(),
            ])
            ->filters([
                Filter::make('Created_at')
                ->form([
                    DatePicker::make('From_date'),
                    DatePicker::make('To_date'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['From_date'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['To_date'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
                ->icon('heroicon-o-document-arrow-down')
                ->url(
                    fn (Order $record): string => route('admin.orders.download', ['id' => $record->id]),
                    shouldOpenInNewTab: true
                ),

            ButtonAction::make('dispatched')
                ->label('Dispatch')
                ->form([
                    // Select::make('order_id')
                    //     ->label('Order Id')
                    //     ->options(Order::all()->pluck('id', 'id')),

                    TextInput::make('LR_number')->label('LR Number'),
                    TextInput::make('transport')->label('Transport'),
                    DatePicker::make('book_date')->label('Book Date')->default(now()),
                    DatePicker::make('delivery_date')->label('Delivery Date')->default(now()),
                ])

                ->action(function (array $data, Order $record) {
                    Dispatch::create([
                        'order_id' => $record->id,
                        'LR_number' => $data['LR_number'],
                        'transport' => $data['transport'],
                        'book_date' => $data['book_date'],
                        'delivery_date' => $data['delivery_date'],
                    ]);

                    $record->update(['status' => 'dispatched']);
                }),

                // Tables\Actions\ReplicateAction::make()
                //     ->action(function (Order $order) {
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
                //     })
                ])
            ->bulkActions([
               
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Download Pdf')
                    ->action(function (Collection $records) {
                        $recordIds = $records->pluck('id')->toArray();

                        if (count($recordIds) === 0) {
                            return response()->json(['message' => 'No records selected.']);
                        }

                        $apiUrl = route('orders.bulk-download', ['order_ids' => $recordIds]); // Replace with your API URL

                        return redirect($apiUrl);
                    })
                    ->icon('heroicon-o-document-arrow-down'),
            
                
                // ExportBulkAction::make('Inventory')
                // ->action(function (Collection $records) {
                //     $recordIds = $records->pluck('id')->toArray();
                //     if (count($recordIds) === 0) {
                //         return response()->json(['message' => 'No records selected.']);
                //     }

                //     $apiUrl = route('orders.SelectedOrders', ['order_ids' => $recordIds]);

                //     return redirect($apiUrl);
                // })
                // ->icon('heroicon-o-document-arrow-down')
                // ->label('Inventory'),

                BulkAction::make('Bulk Dispatch')
                    ->form([
                        TextInput::make('LR_number')->label('LR Number'),
                        TextInput::make('transport')->label('Transport'),
                        DatePicker::make('book_date')->label('Book Date')->default(now()),
                        DatePicker::make('delivery_date')->label('Delivery Date')->default(now()),
                    ])
                    ->action(function (Collection $records, array $data) {
                        $recordIds = $records->pluck('id')->toArray();
                        if (count($recordIds) === 0) {
                            return response()->json(['message' => 'No records selected.']);
                        }
                        foreach ($records as $record) {
                            Dispatch::create([
                                'order_id' => $record->id,
                                'LR_number' => $data['LR_number'],
                                'transport' => $data['transport'],
                                'book_date' => $data['book_date'],
                                'delivery_date' => $data['delivery_date'],
                            ]);
                        }
                        $records->each(function ($record) {
                            $record->update(['status' => 'dispatched']);
                        });
                    }),

                    Tables\Actions\BulkAction::make('Bulk cancel')
                    ->action(function (Collection $records) {
                        $recordIds = $records->pluck('id')->toArray();
                        Order::whereIn('id', $recordIds)->update(['status' => 'cancel']);
                    })->icon('heroicon-o-x-circle'),

                Tables\Actions\BulkAction::make('Bulk Refund')
                    ->action(function (Collection $records) {
                        $recordIds = $records->pluck('id')->toArray();
                        Order::whereIn('id', $recordIds)->update(['status' => 'refund']);
                    })->icon('heroicon-o-arrow-uturn-right'),

            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackingOrders::route('/'),
            'create' => Pages\CreatePackingOrder::route('/create'),
            'edit' => Pages\EditPackingOrder::route('/{record}/edit'),
        ];
    }
}
