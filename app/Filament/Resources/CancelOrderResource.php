<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CancelOrderResource\Pages;
use App\Filament\Resources\CancelOrderResource\RelationManagers;
use App\Models\CancelOrder;
use App\Forms\Components\AddressForm;
use App\Forms\Components\CustomerForm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Dispatch;
use App\Models\BankAccount;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\ButtonAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Redirect;
use App\Models\Orderstatus;
use App\Models\Payment;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Support\Facades\Http;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Support\Collection;

class CancelOrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-x-circle';
    
    protected static ?string $navigationGroup = 'Orders';

    protected static ?string $navigationLabel = 'Cancelled Orders';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'id';


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '=', 'cancelled')->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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

                Forms\Components\Select::make('status')
                        ->options([
                            'placed' => 'placed',
                            'cancelled' => 'cancelled',
                        ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

        ->query(function () {

            $query = Order::query();

            return $query->whereNot('status', 'placed')->latest('created_at');
        })


        ->query(function () {

            $query = Order::query();

            return $query->where('status', 'cancelled')->latest('created_at');
        })

            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Order ID')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('customer.name')->label('Customer name')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('customer.mobile_number')->label('Mobile number')->searchable()->toggleable()->sortable(),
                // Tables\Columns\TextColumn::make('address.address')->toggleable(),
                Tables\Columns\TextColumn::make('address.city_town')->label('City/Town')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('net_total')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('status')->searchable(['customer', 'orderstatus'])->searchable()->toggleable()->sortable(),
                SelectColumn::make('status')
                    ->options([
                        'placed' => 'placed',
                        'cancelled' => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                ->dateTime('d-m-y H:i:s')
                ->sortable()
                ->toggleable(),
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
                // Tables\Actions\Action::make('download')
                //     ->icon('heroicon-o-document-arrow-down')
                //     ->url(
                //         fn (Order $record): string => route('admin.orders.download', ['id' => $record->id]),
                //         shouldOpenInNewTab: true
                //     ),
            ])
            ->bulkActions([
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

                // ExportBulkAction::make()->exports([
                //     ExcelExport::make()->withWriterType(\Maatwebsite\Excel\Excel::CSV),
                // ]),

                Tables\Actions\DeleteBulkAction::make(),
            //     ExportBulkAction::make('Inventory')
            //         ->action(function (Collection $records) {
            //             $recordIds = $records->pluck('id')->toArray();
            //             if (count($recordIds) === 0) {
            //                 return response()->json(['message' => 'No records selected.']);
            //             }

            //             $apiUrl = route('orders.SelectedOrders', ['order_ids' => $recordIds]);

            //             return redirect($apiUrl);
            //         })
            //         ->icon('heroicon-o-document-arrow-down')
            //         ->label('Inventory'),
            // ])->paginated([10, 30, 40, 50, 75, 100 => 'all'
            ])->defaultSort('updated_at', 'desc');
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
            'index' => Pages\ListCancelOrders::route('/'),
            'create' => Pages\CreateCancelOrder::route('/create'),
            'edit' => Pages\EditCancelOrder::route('/{record}/edit'),
        ];
    }    
}
