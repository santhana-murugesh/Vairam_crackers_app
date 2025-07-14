<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Payment;
use App\Models\BankAccount;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ButtonAction;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;


class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationLabel = 'Orders';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Orders';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '=', 'placed')->count();
    }

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('id')
                ->label('Order id'),

            Select::make('customer_id')
                ->label('Customer')
                ->options(Customer::all()->pluck('name', 'id')->filter())
                ->required()
                ->searchable(),

            Forms\Components\TextInput::make('net_total')
                ->required()
                ->maxLength(255),

            Select::make('address_id')
                ->label('Shipping Address')
                ->options(Address::all()->pluck('address', 'id')->filter())
                ->searchable(),

            // Ensure each of these pluck methods returns non-null values
            Select::make('address_id')
                ->label('City')
                ->options(Address::all()->pluck('city_town', 'id')->filter())
                ->searchable(),

            Select::make('address_id')
                ->label('District')
                ->options(Address::all()->pluck('district', 'id')->filter())
                ->searchable(),

            Select::make('address_id')
                ->label('Pin Code')
                ->options(Address::all()->pluck('pin_code', 'id')->filter())
                ->searchable(),
        ])
        ->columns(2);
}

    public static function table(Table $table): Table
    {
        return $table
        ->query(function () {

            $query = Order::query();

            return $query->whereNot('status', 'payment_received')->latest('created_at');
        })

        ->query(function () {

            $query = Order::query();

            return $query->where('status', 'placed')->latest('created_at');
        })

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
                // Tables\Actions\EditAction::make(),
                ButtonAction::make('dispatched')
                ->label('Cancel')
                ->action(function (array $data, Order $record) {
                    $record->update(['status' => 'cancelled']);
                    // return Redirect::to('/admin/dispatches');
                }),
            Tables\Actions\Action::make('download')
                ->icon('heroicon-o-document-arrow-down')
                ->url(
                    fn (Order $record): string => route('admin.orders.download', ['id' => $record->id]),
                    shouldOpenInNewTab: true
                ),

                ButtonAction::make('payment')
                    ->label('Payment')
                    ->form([
                        Select::make('bank_account_id')
                            ->label('Payment Received')
                            ->options(BankAccount::all()->pluck('bank_name', 'id'))
                            ->searchable(),
                            // ->default(BankAccount::first()->id),

                        DateTimePicker::make('payment_received_date')
                            ->default(now())
                            ->label('Payment Received Date'),
                    ])

                    ->action(function (array $data, Payment $record): void {
                        $record->bankAccount()->associate($data['bank_account_id']);
                        $record->save();
                    })

                    ->action(function (array $data, Order $record) {

                        Payment::create([
                            'order_id' => $record->id,
                            'bank_account_id' => $data['bank_account_id'],
                            'payment_received_date' => $data['payment_received_date'],
                        ]);

                        $record->update(['status' => 'payment_received']);
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make('delete')
                        ->action(fn (Collection $records) => $records->each->delete())
                        ->deselectRecordsAfterCompletion(),
                    //     ExportBulkAction::make()->exports([
                    //         ExcelExport::make()->fromTable()->askForWriterType(),
                    //     ]),
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


                        // Tables\Actions\BulkAction::make('Bulk Cancel')
                        // ->action(function (Collection $records) {
                        //     $recordIds = $records->pluck('id')->toArray();
                        //     Order::whereIn('id', $recordIds)->update(['status' => 'cancelled']);
                        // })->icon('heroicon-o-x-circle'),

                        // Tables\Actions\BulkAction::make('Bulk Refund')
                        // ->action(function (Collection $records) {
                        //     $recordIds = $records->pluck('id')->toArray();
                        //     Order::whereIn('id', $recordIds)->update(['status' => 'refund']);
                        // })->icon('heroicon-o-arrow-uturn-right'),

                        Tables\Actions\BulkAction::make('Bulk Payment')
                        ->form([
                            Select::make('bank_account_id')
                                ->label('Payment Received')
                                ->options(BankAccount::all()->pluck('bank_name', 'id'))
                                ->searchable(),
                                // ->default(BankAccount::first()->id),

                            DateTimePicker::make('payment_received_date')
                                ->default(now())
                                ->label('Payment Received Date'),
                        ])->action(function (Collection $records, array $data) {
                            $recordIds = $records->pluck('id')->toArray();
                            if (count($recordIds) === 0) {
                                return response()->json(['message' => 'No records selected.']);
                            }
                            foreach ($records as $record) {
                                Payment::create([
                                    'order_id' => $record->id,
                                    'bank_account_id' => $data['bank_account_id'],
                                    'payment_received_date' => $data['payment_received_date'],
                                ]);
                            }
                            $records->each(function ($record) {
                                $record->update(['status' => 'payment_received']);
                            });
                        })->icon('heroicon-o-x-circle'),
                    ])->paginated([10, 30, 40, 50, 75, 100 => 'all'])
                    ->defaultSort('created_at', 'desc');

    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    // public static function getWidgets(): array
    // {
    //     return [
    //         OrderStatsOverview::class,
    //     ];
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
