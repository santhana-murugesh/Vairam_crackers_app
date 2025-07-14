<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillOrderResource\Pages;
use App\Filament\Resources\BillOrderResource\RelationManagers;
use App\Models\BillOrder;
use App\Models\Customer;
use App\Models\Address;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;


class BillOrderResource extends Resource
{
    protected static ?string $model = BillOrder::class;

    // protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationIcon = 'heroicon-o-currency-rupee';

    protected static ?string $navigationGroup = 'Billings';

    protected static ?string $navigationLabel = 'BillingOrder';
    
    protected static ?int $navigationSort = 2;
      // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::count();
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([

                        Select::make('customer_id')
                            ->label('Customer')
                            ->options(Customer::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\TextInput::make('discount_total')
                            ->required()
                            ->maxLength(255),
                
                        Forms\Components\TextInput::make('net_total')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('sub_total')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('sgst')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('cgst')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('igst')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('gst_no')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('aadhar_no')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('gst_total')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('total')
                            ->required()
                            ->maxLength(255),

                        Select::make('state_id')
                            ->relationship('state', 'name')
                            ->searchable()
                            ->preload(),

                            Select::make('address_id')
                            ->label('Shipping Address')
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


                    ])->columns(3)


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Order ID')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('bill_no')
                ->label('Bill No')
                ->sortable()   // Allows sorting by bill number
                ->searchable(), // Allows searching by bill number
               
                Tables\Columns\TextColumn::make('customer.name')->label('Customer name')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('gst_no')->label('GST No')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('aadhar_no')->label('AADHAR No')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('customer.mobile_number')->label('Mobile number')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Ordered date')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('sub_total')->searchable()->toggleable()->sortable(),
                BadgeColumn::make('status')->searchable(['customer', 'orderstatus'])->searchable()->toggleable()->sortable()
                    ->colors([
                        'danger' => 'cancelled',
                        'warning' => 'pending',
                        'primary' =>  'payment_received',
                        'gray'    =>  'dispatched',
                        'info' => 'placed',
                        'success' => fn ($state) => in_array($state, ['delivered', 'shipped']),
                    ]),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(
                        fn (BillOrder $record): string => route('admin.bill-orders.download', ['id' => $record->id]),
                        shouldOpenInNewTab: true
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BillItemsRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBillOrders::route('/'),
            'create' => Pages\CreateBillOrder::route('/create'),
            'edit' => Pages\EditBillOrder::route('/{record}/edit'),
        ];
    }
}
