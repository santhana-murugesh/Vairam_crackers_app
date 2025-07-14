<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CombopackPaymentsResource\Pages;
use App\Filament\Resources\CombopackPaymentsResource\RelationManagers;
use App\Models\BankAccount;
use App\Models\ComboPackOrder;
use App\Models\ComboPackPayment;
use App\Models\CombopackPayments;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CombopackPaymentsResource extends Resource
{
    protected static ?string $model = ComboPackPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Customers';
    protected static ?int $navigationSort = 4;

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

                        Select::make('combo_pack_order_id')
                            ->label('Order id')
                            ->options(ComboPackOrder::all()->pluck('id', 'id'))
                            ->required()
                            ->searchable(),

                        Select::make('bank_account_id')
                        ->label('BankAccount')
                        ->options(BankAccount::all()->pluck('bank_name', 'id'))
                        ->searchable()
                        ->required(),

                        DatePicker::make('payment_received_date')->label('Payment Received Date')->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('combo_pack_order_id')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('bankAccount.bank_name')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('payment_received_date')->searchable()
                    ->dateTime('d-m-y')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCombopackPayments::route('/'),
            'create' => Pages\CreateCombopackPayments::route('/create'),
            'edit' => Pages\EditCombopackPayments::route('/{record}/edit'),
        ];
    }
}
