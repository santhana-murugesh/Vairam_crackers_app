<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefundOrdersResource\Pages;
use App\Filament\Resources\RefundOrdersResource\RelationManagers\ItemsRelationManager;
use App\Forms\Components\AddressForm;
use App\Forms\Components\CustomerForm;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Address;
use App\Models\RefundOrders;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;



class RefundOrdersResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-right';

    protected static ?string $navigationGroup = 'Orders';
    protected static ?string $navigationLabel = 'Refund Orders';
    protected static ?int $navigationSort = 7;
    protected static ?string $recordTitleAttribute = 'id';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '=', 'refund')->count();
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

                Section::make('Order Summary')

                    ->schema([

                        Forms\Components\TextInput::make('net_total')
                            ->reactive()
                            ->required(),
                            Select::make('address_id')
                            ->label('shipping address')
                            ->options(Address::all()->pluck('address', 'id'))
                            ->searchable(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'placed' => 'placed',
                                'cancelled' => 'cancelled',
                                'refund' => 'refund',
                            ])->required(),

                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(function () {

                $query = Order::query();

                return $query->where('status', 'refund')->latest('created_at');
            })
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Order ID')->toggleable()->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer.name')->label('Customer name')->toggleable()->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer.mobile_number')->label('Mobile number')->toggleable()->searchable()->sortable()->copyable(),
                Tables\Columns\TextColumn::make('address.city_town')->label('City/Town')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('address.pincode')->label('Pincode')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('net_total')->toggleable()->searchable()->sortable(),
                Tables\Columns\TextColumn::make('notes')->toggleable(isToggledHiddenByDefault: true)->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')->label('Ordered date')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Updated date')->searchable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Bulk Place')
                    ->action(function (Collection $records) {
                        $recordIds = $records->pluck('id')->toArray();
                        Order::whereIn('id', $recordIds)->update(['status' => 'placed']);
                    })->icon('heroicon-o-x-circle'),
                ]),
            ])->paginated([10, 30, 40, 50, 75, 100 => 'all']);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array

    {
        return [
            'index' => Pages\ListRefundOrders::route('/'),
            'create' => Pages\CreateRefundOrders::route('/create'),
            'edit' => Pages\EditRefundOrders::route('/{record}/edit'),
        ];
    }
}
