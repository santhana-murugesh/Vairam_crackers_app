<?php
namespace App\Filament\Resources;
use App\Filament\Resources\AllComboPackOrdersResource\Pages;
use App\Filament\Resources\AllComboPackOrdersResource\RelationManagers;
use App\Models\ComboPackOrder;
use App\Models\Order;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Card;
use App\Forms\Components\AddressForm;
use App\Forms\Components\CustomerForm;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ButtonAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
class AllComboPackOrdersResource extends Resource
{
    protected static ?string $model = ComboPackOrder::class;
    protected static ?string $navigationGroup = 'ComboPack Orders';
    protected static ?string $navigationLabel = 'AllComboPackOrders';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 8;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
                Forms\Components\TextInput::make('net_total')
                ->required()
                ->numeric()
                ->maxLength(255),
                Forms\Components\TextInput::make('sub_total')
                ->required()
                ->numeric()
                ->maxLength(255),
                Forms\Components\Select::make('status')
                ->options([
                    'placed' => 'placed',
                    'pending' => 'pending',
                    'payment_received' => 'payment_received',
                    'dispatched' => 'dispatched',
                    'delivered' => 'delivered',
                    'cancelled' => 'cancelled',
                ])
                ->required(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('customer.mobile_number')->label('Mobile_number')->toggleable()->searchable(),
                // Tables\Columns\TextColumn::make('customer.whatsapp_number')->toggleable()->searchable(),
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
                        fn (ComboPackOrder $record): string => route('admin.combo.download', ['id' => $record->id]),
                        shouldOpenInNewTab: true
                    ),
                    // Tables\Actions\ReplicateAction::make()
                    // ->action(function (ComboPackOrder $combopackorder) {
                    //     // Ensure you use ComboPackOrder instead of Model or Order
                    //     $orderToClone = ComboPackOrder::with('customer', 'address', 'items.combo_pack')->find($combopackorder->id);
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
                // Tables\Actions\BulkAction::make('Download Pdf')
                //     ->action(function (Collection $records) {
                //         $recordIds = $records->pluck('id')->toArray();
                //         if (count($recordIds) === 0) {
                //             return response()->json(['message' => 'No records selected.']);
                //         }
                //         $apiUrl = route('orders.bulk-download', ['order_ids' => $recordIds]);
                //         return redirect($apiUrl);
                //     })
                //     ->icon('heroicon-o-document-arrow-down'),
                // ExportBulkAction::make('Inventory')
    //                 ->action(function (Collection $records) {
    //                     $recordIds = $records->pluck('id')->toArray();
    //                     if (count($recordIds) === 0) {
    //                         return response()->json(['message' => 'No records selected.']);
    //                     }
    //                     $apiUrl = route('orders.SelectedOrders', ['order_ids' => $recordIds]);
    //                     return redirect($apiUrl);
    //                 })
    //                 ->icon('heroicon-o-document-arrow-down')
    //                 ->label('Inventory'),
    //         ])->paginated([10, 30, 40, 50, 75, 100 => 'all'
    ]);
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
            //
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAllCombopackorders::route('/'),
            'create' => Pages\CreateAllCombopackorders::route('/create'),
            'edit' => Pages\EditAllCombopackorders::route('/{record}/edit'),
        ];
    }
}