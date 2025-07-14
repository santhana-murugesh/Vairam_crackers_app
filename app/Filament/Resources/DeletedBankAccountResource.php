<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeletedBankAccountResource\Pages;
use App\Filament\Resources\DeletedBankAccountResource\RelationManagers;
use App\Models\BankAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeletedBankAccountResource extends Resource
{
    protected static ?string $model = BankAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Deleted Bank Accounts';

    protected static ?string $navigationGroup = "Admin";
     
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::onlyTrashed()->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('bank_name')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('branch')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('account_number')
                ->required(),

                Forms\Components\TextInput::make('ifsc_code')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(function () {

            $query = BankAccount::query();

            return $query->onlyTrashed();
        })
            ->columns([
                Tables\Columns\TextColumn::make('bank_name')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('branch')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('account_number')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('ifsc_code')->searchable()->toggleable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\RestoreAction::make(),
                //  Tables\Actions\DeleteBulkAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
           
        ];
    }

    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeletedBankAccounts::route('/'),
            'create' => Pages\CreateDeletedBankAccount::route('/create'),
            'edit' => Pages\EditDeletedBankAccount::route('/{record}/edit'),
        ];
    }
}
