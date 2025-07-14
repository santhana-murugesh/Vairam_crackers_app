<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Customers';
    // protected static ?string $recordTitleAttribute = 'orders.id';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Card::make()
            ->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            // Forms\Components\TextInput::make('email')
            //     ->required()
            //     ->maxLength(255),
            Forms\Components\TextInput::make('mobile_number')
                ->required(),

            Forms\Components\TextInput::make('whatsapp_number')
                ->required(),
            ])

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->toggleable()->sortable(),
                // Tables\Columns\TextColumn::make('email')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('mobile_number')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('whatsapp_number')->searchable()->toggleable()->sortable(),
                TextColumn::make('name')->searchable(['name', 'mobile_number'])->searchable()->toggleable()->sortable(),
                TextColumn::make('name')->searchable(['name', 'whatsapp_number'])->searchable()->toggleable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AddressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
