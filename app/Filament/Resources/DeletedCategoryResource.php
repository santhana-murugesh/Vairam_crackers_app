<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeletedCategoryResource\Pages;
use App\Filament\Resources\DeletedCategoryResource\RelationManagers;
use App\Models\Category;
use App\Models\DeletedCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeletedCategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Deleted Categories';
    protected static ?string $navigationGroup = 'Categories';
    protected static ?int $navigationSort = 3;


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::onlyTrashed()->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('category')
                ->required()
                ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(function () {

            $query = Category::query();

            return $query->onlyTrashed();
        })
            ->columns([
                Tables\Columns\TextColumn::make('category')->searchable()->toggleable()->sortable(),
                TextColumn::make('category')->searchable()->toggleable()->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListDeletedCategories::route('/'),
            'create' => Pages\CreateDeletedCategory::route('/create'),
            'edit' => Pages\EditDeletedCategory::route('/{record}/edit'),
        ];
    }
}
