<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AllOrdersResource\Pages;
use App\Filament\Resources\AllOrdersResource\RelationManagers;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Address;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class AllOrdersResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationLabel = 'AllOrders';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Orders';

    protected static ?int $navigationSort = 8;

    protected static ?string $recordTitleAttribute = 'id';


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(Form $form): Form
    {
        return $form
        ->schema([

                    Forms\Components\TextInput::make('id')
                    ->label('Order id'),

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
                        ->options(Address::all()->pluck('address', 'id')->map(fn($item) => $item ?? 'N/A'))
                        ->searchable(),

                    Select::make('address_id')
                        ->label(' city')
                        ->options(Address::all()->pluck('city_town', 'id')->map(fn($item) => $item ?? 'N/A'))
                        ->searchable(),

                    Select::make('address_id')
                        ->label(' district')
                        ->options(Address::all()->pluck('district', 'id')->map(fn($item) => $item ?? 'N/A'))
                        ->searchable(),

                    Select::make('address_id')
                        ->label(' Pin Code')
                        ->options(Address::all()->pluck('pin_code', 'id')->map(fn($item) => $item ?? 'N/A'))
                        ->searchable(),

                ])->columns(2);


    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Order id')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('customer.name')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('net_total')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('address.address')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('address.city_town')->label('City/Town')->searchable()->toggleable()->sortable(),
                // Tables\Columns\TextColumn::make('address.district')->label('District')->searchable()->toggleable()->sortable(),
                // Tables\Columns\TextColumn::make('address.pin_code')->label('Pin code')->searchable()->toggleable()->sortable(),
                SelectColumn::make('status')
                ->options([
                    'placed' => 'placed',
                    'payment_received' => 'payment_received',
                    'dispatched' => 'dispatched',
                    'delivered' => 'delivered',
                    'cancelled' => 'cancelled',
                ]),
                Tables\Columns\TextColumn::make('created_at')->searchable()
                ->dateTime('d-m-y H:i:s')
                ->sortable()
                ->toggleable(),
            // Tables\Columns\TextColumn::make('updated_at')->searchable()
            //     ->dateTime('d-m-y H:i:s')
            //     ->sortable()
            //     ->toggleable(),

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
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\Action::make('Download')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(
                        fn (Order $record): string => route('admin.orders.download', ['id' => $record->id]),
                        shouldOpenInNewTab: true
                    ),
                    // Tables\Actions\ReplicateAction::make()
                    // ->action(function (Order $order) {
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
                ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                // ExportBulkAction::make()->exports([
                //     ExcelExport::make()->fromTable()->askForWriterType(),
                // ]),
                Tables\Actions\BulkAction::make('Download Pdf')
                    ->action(function (Collection $records) {
                        $recordIds = $records->pluck('id')->toArray();

                        if (count($recordIds) === 0) {
                            return response()->json(['message' => 'No records selected.']);
                        }

                        $apiUrl = route('orders.bulk-download', ['order_ids' => $recordIds]);

                        return redirect($apiUrl);
                    })
                    ->icon('heroicon-o-document-arrow-down'),

                // ExportBulkAction::make('Inventory')
                //     // ->action(function (Collection $records) {
                    //     $recordIds = $records->pluck('id')->toArray();
                    //     if (count($recordIds) === 0) {
                    //         return response()->json(['message' => 'No records selected.']);
                    //     }

                    //     $apiUrl = route('orders.SelectedOrders', ['order_ids' => $recordIds]);

                    //     return redirect($apiUrl);
                    // })
                    // ->icon('heroicon-o-document-arrow-down')
                    // ->label('Inventory'),

                    Tables\Actions\BulkAction::make('Bulk Cancel')
                    ->action(function (Collection $records) {
                        $recordIds = $records->pluck('id')->toArray();
                        Order::whereIn('id', $recordIds)->update(['status' => 'cancelled']);
                    })->icon('heroicon-o-x-circle'),

                    Tables\Actions\BulkAction::make('Bulk Refund')
                    ->action(function (Collection $records) {
                        $recordIds = $records->pluck('id')->toArray();
                        Order::whereIn('id', $recordIds)->update(['status' => 'refund']);
                    })->icon('heroicon-o-arrow-uturn-right'),

            ])->paginated([10, 30, 40, 50, 75, 100 => 'all']);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()

            ->withoutGlobalScopes([
                SoftDeletingScope::class,
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
            'index' => Pages\ListAllOrders::route('/'),
            'create' => Pages\CreateAllOrders::route('/create'),
            'edit' => Pages\EditAllOrders::route('/{record}/edit'),
        ];
    }
}
