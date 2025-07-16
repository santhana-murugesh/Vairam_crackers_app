<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AllProductResource\Pages;
use App\Filament\Resources\AllProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\MultiSelect;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AllProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = "All Products";

    protected static ?string $navigationGroup = "Products";

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

                // Forms\Components\TextInput::make('url_slug')
                // ->maxLength(255),

                // Forms\Components\TextInput::make('seo_title')
                // ->maxLength(255),

                // RichEditor::make('description'),

                Select::make('category_id')
                        ->label('category')
                        ->relationship('category', 'category')
                        ->required()
                        ->preload()
                        ->searchable(),

                Forms\Components\TextInput::make('price')
                    ->required()
                    ->maxLength(255),

                FileUpload::make('image')
                    ->acceptedFileTypes([
                        'image/jpeg',
                        'image/jpg', 
                        'image/png',
                        'image/gif',
                        'image/webp',
                        'image/svg+xml'
                    ])
                    ->maxSize(5120)  // 5MB max size
                    ->imageEditor()
                    ->imagePreviewHeight('200')
                    ->loadingIndicatorPosition('center')
                    ->panelAspectRatio('16:9')
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadButtonPosition('left')
                    ->uploadProgressIndicatorPosition('left')
                    ->helperText('Supported formats: JPG, PNG, GIF, WebP, SVG (Max: 5MB)')
                    ->rules([
                        'mimes:jpeg,jpg,png,gif,webp,svg',
                        'max:5120' // 5MB
                    ])
                    ->directory('products')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->toggleable()->sortable(),
                // Tables\Columns\TextColumn::make('url_slug')->searchable()->toggleable()->sortable(),
                // Tables\Columns\TextColumn::make('seo_title')->searchable()->toggleable()->sortable(),
                // Tables\Columns\TextColumn::make('description')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('category.category')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('price')->searchable()->toggleable()->sortable(),
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListAllProducts::route('/'),
            'create' => Pages\CreateAllProduct::route('/create'),
            'edit' => Pages\EditAllProduct::route('/{record}/edit'),
        ];
    }    
}
