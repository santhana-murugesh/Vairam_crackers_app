<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeletedProductResource\Pages;
use App\Filament\Resources\DeletedProductResource\RelationManagers;
use App\Models\Product;
use Doctrine\DBAL\Schema\Column;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;



class DeletedProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Products';

    protected static ?string $navigationLabel = 'Deleted Products';
    
    protected static ?int $navigationSort = 2;


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::onlyTrashed()->count();
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
            ->query(function () {

                $query = Product::query();

                return $query->onlyTrashed();
            })
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
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
                // ExportBulkAction::make(),
                
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
            'index' => Pages\ListDeletedProducts::route('/'),
            'create' => Pages\CreateDeletedProduct::route('/create'),
            'edit' => Pages\EditDeletedProduct::route('/{record}/edit'),
        ];
    }
}
