<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'Address';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('address')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('district')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('city_town')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('state')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('pin_code')
                ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('district'),
                Tables\Columns\TextColumn::make('city_town'),
                Tables\Columns\TextColumn::make('state'),
                Tables\Columns\TextColumn::make('pin_code'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
