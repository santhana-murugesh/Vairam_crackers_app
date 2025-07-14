<?php
namespace App\Filament\Resources;
use App\Filament\Resources\ComboPackConfirmOrderResource\Pages;
use App\Filament\Resources\ComboPackConfirmOrderResource\RelationManagers;
use App\Models\ComboPackOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use App\Forms\Components\AddressForm;
use App\Forms\Components\CustomerForm;
use App\Models\Order;
use App\Models\BankAccount;
use App\Models\Payment;
use App\Models\Orderstatus;
use App\Models\Customer;
use App\Models\Address;
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
use Illuminate\Support\Collection;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
class ComboPackConfirmOrderResource extends Resource
{
    protected static ?string $model = ComboPackOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'ComboPack Orders';
    protected static ?string $navigationLabel = 'ComboPack Confirm Order';
    protected static ?int $navigationSort = 2;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '=', 'payment_received')->count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('customer_id')
                ->label('Customer id')
                ->options(Customer::all()->pluck('name', 'id'))
                ->required()
                ->searchable(),
                Forms\Components\TextInput::make('net_total')->numeric()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sub_total')->numeric()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'placed' => 'placed',
                        'payment_received' => 'payment_received',
                        'cancelled' => 'cancelled',
                        'packing' => 'packing',
                    ]),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return ComboPackOrder::where('status', 'payment_received')->latest('created_at');
            })
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('customer.mobile_number')->label('mobile_number')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('address.address')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('net_total')->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d-m-y H:i:s')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
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
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['To_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(
                        fn (ComboPackOrder $record): string => route('admin.combo.download', ['id' => $record->id]),
                        shouldOpenInNewTab: true
                    ),
                ButtonAction::make('packing')
                    ->label('Packing')
                    ->action(function (ComboPackOrder $record) {
                        $record->update(['status' => 'packing']);
                    }),
                // Tables\Actions\Action::make('Clone')
                //     ->action(function (ComboPackOrder $orderToClone) {
                //         // Eager load necessary relationships
                //         $orderToClone = ComboPackOrder::with('customer', 'address', 'items.combo_pack')->find($orderToClone->id);
                //         if ($orderToClone) {
                //             $cloneOrder = $orderToClone->replicate();
                //             $cloneOrder->created_at = now();
                //             $cloneOrder->save();
                //             // Check if customer relationship exists
                //             if ($orderToClone->customer) {
                //                 $cloneCustomer = $orderToClone->customer->replicate();
                //                 $cloneCustomer->save();
                //                 $cloneOrder->customer()->associate($cloneCustomer);
                //             }
                //             // Check if address relationship exists
                //             if ($orderToClone->address) {
                //                 $cloneAddress = $orderToClone->address->replicate();
                //                 $cloneAddress->save();
                //                 $cloneOrder->address()->associate($cloneAddress);
                //             }
                //             $cloneItems = collect();
                //             // Check if items relationship exists
                //             if ($orderToClone->items) {
                //                 foreach ($orderToClone->items as $itemToClone) {
                //                     $clonedItem = $itemToClone->replicate();
                //                     $clonedItem->save();
                //                     $cloneItems->push($clonedItem);
                //                 }
                //             }
                //             $cloneOrder->save();
                //             // Associate cloned items to cloned order
                //             $cloneOrder->items()->saveMany($cloneItems);
                //             return $cloneOrder->id;
                //         }
                //         return null;
                //     })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('Download pdf')
                        ->action(function (Collection $records) {
                            $recordIds = $records->pluck('id')->toArray();
                            if (count($recordIds) === 0) {
                                return response()->json(['message' => 'No records selected.']);
                            }
                            $apiUrl = route('orders.bulk-download', ['order_ids' => $recordIds]);
                            return redirect($apiUrl);
                        })
                        ->icon('heroicon-o-document-arrow-down'),
                    Tables\Actions\DeleteBulkAction::make(),
                    // ExportBulkAction::make('Inventory')
                    //     ->action(function (Collection $records) {
                    //         $recordIds = $records->pluck('id')->toArray();
                    //         if (count($recordIds) === 0) {
                    //             return response()->json(['message' => 'No records selected.']);
                    //         }
                    //         $apiUrl = route('combo.SelectedOrders', ['combo_pack_ids' => $recordIds]);
                    //         return redirect($apiUrl);
                    //     })
                    //     ->icon('heroicon-o-document-arrow-down')
                    //     ->label('Inventory'),
                //     Tables\Actions\BulkAction::make('Bulk Packing')
                //         ->action(function (Collection $records) {
                //             $recordIds = $records->pluck('id')->toArray();
                //             Order::whereIn('id', $recordIds)->update(['status' => 'packing']);
                //         })
                //         ->icon('heroicon-o-gift'),
                //     Tables\Actions\BulkAction::make('Bulk Refund')
                //         ->action(function (Collection $records) {
                //             $recordIds = $records->pluck('id')->toArray();
                //             Order::whereIn('id', $recordIds)->update(['status' => 'refund']);
                //         })
                //         ->icon('heroicon-o-arrow-uturn-right'),
                ]),
            ]);
    }
    public static function getRelations(): array
    {
        return [];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComboPackConfirmOrders::route('/'),
            'create' => Pages\CreateComboPackConfirmOrder::route('/create'),
            'edit' => Pages\EditComboPackConfirmOrder::route('/{record}/edit'),
        ];
    }
}