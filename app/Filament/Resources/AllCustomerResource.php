<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AllCustomerResource\Pages;
use App\Filament\Resources\AllCustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AllCustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Customers';
    protected static ?string $navigationLabel = 'All Customers';

    protected static ?int $navigationSort = 2;

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
                Tables\Columns\TextColumn::make('name')->toggleable(),
                // Tables\Columns\TextColumn::make('email')->toggleable(),
                Tables\Columns\TextColumn::make('mobile_number')->toggleable(),
                Tables\Columns\TextColumn::make('whatsapp_number')->toggleable(),
                TextColumn::make('name')->searchable(['name', 'mobile_number'])->toggleable(),
                TextColumn::make('name')->searchable(['name', 'whatsapp_number'])->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListAllCustomers::route('/'),
            'create' => Pages\CreateAllCustomer::route('/create'),
            'edit' => Pages\EditAllCustomer::route('/{record}/edit'),
        ];
    }
}
