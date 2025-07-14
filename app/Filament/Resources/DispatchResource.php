<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AllOrdersResource\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\DispatchResource\Pages;
use App\Filament\Resources\DispatchResource\RelationManagers;
use App\Models\Dispatch;
use App\Forms\Components\AddressForm;
use App\Forms\Components\CustomerForm;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Address;
use App\Models\OrderItem;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Card;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;


class DispatchResource extends Resource
{
    protected static ?string $model = Dispatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationGroup = 'Orders';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '=', 'dispatched')->count();
    }
    public static $related = ItemsRelationManager::class;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section::make('Customer Details')
                // ->schema([
                    // Select::make('customer_id')
                    //     ->label('Customer')
                    //     ->options(Customer::all()->pluck('name', 'id'))
                    //     ->required()
                    //     ->searchable(),

                // ]),
                // Select::make('addressr_id')
                // ->label('Address')
                // ->options(Address::all()->pluck('name', 'id'))
                // ->required()
                // ->searchable(),
            Section::make('Dispatch Details')
                ->schema([
                    Select::make('order_id')
                        ->label('Order_Id')
                        ->options(Order::all()->pluck('id', 'id'))
                        ->searchable(),

                    Forms\Components\TextInput::make('LR_number')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('transport')
                        ->required()
                        ->maxLength(255),

                    DateTimePicker::make('book_date'),

                    DateTimePicker::make('delivery_date'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')->label('Order_Id')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('LR_number')->label('LR Number')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('transport')->label('Transport')->searchable()->toggleable()->sortable(),
                TextColumn::make('order.customer.name')
                    ->label('Customer Name')
                    ->searchable(),
                TextColumn::make('order.customer.mobile_number')
                    ->label('Mobile Number')
                    ->searchable(),
                TextColumn::make('order.address.city_town')
                    ->label('City')
                    ->searchable(),
                Tables\Columns\TextColumn::make('book_date')->searchable()
                    ->dateTime('d-m-y H:i:s')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('delivery_date')->label('Delivery_Date')->searchable()
                    ->dateTime('d-m-y H:i:s')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Updated date')->searchable()->toggleable(isToggledHiddenByDefault: true),

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
                })
            ])


            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            
            ->bulkActions([
                // ExportBulkAction::make()->exports([
                //     ExcelExport::make()->fromTable()->askForWriterType(),
                // ]),
                Tables\Actions\DeleteBulkAction::make(),
                // ExportBulkAction::make('inventory')
                //     ->action(function (Collection $records) {
                //         $recordIds = $records->pluck('id')->toArray();
                //         if (count($recordIds) === 0) {
                //             return response()->json(['message' => 'No records selected.']);
                //         }

                //         $apiUrl = route('orders.SelectedOrders', ['order_ids' => $recordIds]);

                //         return redirect($apiUrl);
                //     })
                //     ->icon('heroicon-o-document-arrow-down')
                //     ->label('Inventory'),

                Tables\Actions\BulkAction::make('Bulk Refund')
                    ->action(function (Collection $records) {
                        $recordIds = $records->pluck('id')->toArray();
                        Order::whereIn('id', $recordIds)->update(['status' => 'refund']);
                    })->icon('heroicon-o-arrow-uturn-right'),

                Tables\Actions\BulkAction::make('Bulk Cancel')
                    ->action(function (Collection $records) {
                        $recordIds = $records->pluck('id')->toArray();
                        Order::whereIn('id', $recordIds)->update(['status' => 'cancel']);
                    })->icon('heroicon-o-x-circle'),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])->paginated([10, 30, 40, 50, 75, 100 => 'all'])->defaultSort('created_at', 'desc');
            
    }
    
    public static function getRelations(): array
    {
        return [
            // RelationManagers\ItemsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDispatches::route('/'),
            'create' => Pages\CreateDispatch::route('/create'),
            'edit' => Pages\EditDispatch::route('/{record}/edit'),
        ];
    }    
}
