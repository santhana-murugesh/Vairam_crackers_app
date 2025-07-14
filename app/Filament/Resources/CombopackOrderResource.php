<?php
namespace App\Filament\Resources;
use App\Filament\Resources\ComboPackOrderResource\Pages;
use App\Filament\Resources\ComboPackOrderResource\RelationManagers;
use App\Models\ComboPackOrder;
use App\Models\ComboPackPayment;
use App\Models\BankAccount;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Address;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Card;
use App\Forms\Components\AddressForm;
use App\Forms\Components\CustomerForm;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\Action as ButtonAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\Action;
use Filament\Tables\Columns\SelectColumn;
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
use App\Http\Controllers\ComboPackController;
class CombopackOrderResource extends Resource
{
    protected static ?string $model = ComboPackOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'ComboPack Orders';
    protected static ?string $navigationLabel = 'ComboPack Order';
    protected static ?int $navigationSort = 1;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '=', 'placed')->count();
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
                Forms\Components\TextInput::make('delivery_location')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('whatsapp_number')->numeric()
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('sub_total')->numeric()
                ->required()
                ->maxLength(255),
                Forms\Components\Select::make('status')
                            ->options([
                                'placed' => 'placed',
                                'cancelled' => 'cancelled',
                            ])
                            ->required(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
        ->query(function () {
            $query = ComboPackOrder::query();
            return $query->where('status', 'placed')->latest('created_at');
        })
            ->columns([
                // Tables\Columns\TextColumn::make('customer.id')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('customer.name')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('customer.mobile_number')->label('Mobile_number')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('address.address')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('net_total')->searchable(),
                SelectColumn::make('status')
                ->options([
                    'placed' => 'placed',
                    'payment_received' => 'payment_received',
                    'cancelled' => 'cancelled',
                ]),
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
                ButtonAction::make('dispatched')
                ->label('Cancel')
                ->action(function (array $data, ComboPackOrder $record) {
                    $record->update(['status' => 'cancelled']);
                    // return Redirect::to('/admin/dispatches');
                }),
            Tables\Actions\Action::make('download')
                ->icon('heroicon-o-document-arrow-down')
                ->url(
                    fn (ComboPackOrder $record): string => route('admin.combo.download', ['id' => $record->id]),
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
                    ->action(function (array $data, ComboPackPayment $record): void {
                        $record->bankAccount()->associate($data['bank_account_id']);
                        $record->save();
                    })
                    ->action(function (array $data, ComboPackOrder $record) {
                        ComboPackPayment::create([
                            'combo_pack_order_id' => $record->id,
                            'bank_account_id' => $data['bank_account_id'],
                            'payment_received_date' => $data['payment_received_date'],
                        ]);
                        $record->update(['status' => 'payment_received']);
                    }),
                    Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make('delete')
                        ->action(fn (Collection $records) => $records->each->delete())
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('Download pdf')
                    ->action(function (Collection $records) {
                        $recordIds = $records->pluck('id')->toArray();
                        // Check if $recordIds is null or empty
                        if (empty($recordIds)) {
                            return response()->json(['message' => 'No records selected.']);
                        }
                        $apiUrl = route('combo.bulk-download', ['order_ids' => $recordIds]);
                        return redirect($apiUrl);
                    })
                    ->icon('heroicon-o-document-arrow-down'),
                        // ExportBulkAction::make('Inventory')
                        // ->action(function (Collection $records) {
                        //     $recordIds = $records->pluck('id')->toArray();
                        //     if (count($recordIds) === 0) {
                        //         return response()->json(['message' => 'No records selected.']);
                        //     }
                        //     $apiUrl = route('combo.SelectedOrders', ['combo_pack_ids' => $recordIds]);
                        //     return redirect($apiUrl);
                        // })
                        // ->icon('heroicon-o-document-arrow-down')
                        // ->label('Inventory'),
                        Tables\Actions\BulkAction::make('Bulk Cancel')
                        ->action(function (Collection $records) {
                            $recordIds = $records->pluck('id')->toArray();
                            ComboPackOrder::whereIn('id', $recordIds)->update(['status' => 'cancelled']);
                        })->icon('heroicon-o-x-circle'),
                        Tables\Actions\BulkAction::make('Bulk Refund')
                        ->action(function (Collection $records) {
                            $recordIds = $records->pluck('id')->toArray();
                            ComboPackOrder::whereIn('id', $recordIds)->update(['status' => 'refund']);
                        })->icon('heroicon-o-arrow-uturn-right'),
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
                                ComboPackPayment::create([
                                    'combo_pack_order_id' => $record->id,
                                    'bank_account_id' => $data['bank_account_id'],
                                    'payment_received_date' => $data['payment_received_date'],
                                ]);
                            }
                            $records->each(function ($record) {
                                $record->update(['status' => 'payment_received']);
                            });
                        })->icon('heroicon-o-x-circle'),
                    ]),
                    ])->paginated([10, 30, 40, 50, 75, 100 => 'all'])
                    ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListComboPackOrders::route('/'),
            'create' => Pages\CreateComboPackOrder::route('/create'),
            'edit' => Pages\EditComboPackOrder::route('/{record}/edit'),
        ];
    }
}