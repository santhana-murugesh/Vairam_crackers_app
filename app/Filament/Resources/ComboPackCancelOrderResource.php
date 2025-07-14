<?php
namespace App\Filament\Resources;
use App\Filament\Resources\ComboPackCancelOrderResource\Pages;
use App\Filament\Resources\ComboPackCancelOrderResource\RelationManagers;
use App\Models\ComboPackOrder;
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
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Models\Customer;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
class ComboPackCancelOrderResource extends Resource
{
    protected static ?string $model = ComboPackOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-x-circle';
    protected static ?string $navigationGroup = 'ComboPack Orders';
    protected static ?string $navigationLabel = 'ComboPack cancelOrder';
    protected static ?int $navigationSort = 5;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '=', 'cancelled')->count();
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
                    'cancelled' => 'cancelled',
                ])->required(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
        ->query(function () {
            $query = ComboPackOrder::query();
            return $query->whereNot('status', 'placed');
        })
        ->query(function () {
            $query = ComboPackOrder::query();
            return $query->where('status', 'cancelled');
        })
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('customer.mobile_number')->label('mobile_number')->toggleable()->searchable(),
                // Tables\Columns\TextColumn::make('customer.whatsapp_number')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('address.address')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('net_total')->searchable(),
                Tables\Columns\TextColumn::make('status')->searchable(['customer', 'orderstatus'])->searchable()->toggleable(),
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
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(
                        fn (ComboPackOrder $record): string => route('admin.combo.download', ['id' => $record->id]),
                        shouldOpenInNewTab: true
                    ),
            ])
            ->bulkActions([
                // Tables\Actions\BulkAction::make('Download Pdf')
                //     ->action(function (Collection $records) {
                //         $recordIds = $records->pluck('id')->toArray();
                //         if (count($recordIds) === 0) {
                //             return response()->json(['message' => 'No records selected.']);
                //         }
                //         $apiUrl = route('orders.bulk-download', ['order_ids' => $recordIds]); // Replace with your API URL
                //         return redirect($apiUrl);
                //     })
                //     ->icon('heroicon-o-document-arrow-down'),
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
            //             $apiUrl = route('combo.SelectedOrders', ['combo_pack_ids' => $recordIds]);
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
            //
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComboPackCancelOrders::route('/'),
            'create' => Pages\CreateComboPackCancelOrder::route('/create'),
            'edit' => Pages\EditComboPackCancelOrder::route('/{record}/edit'),
        ];
    }
}