<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeaturedProductResource\Pages;
use App\Filament\Resources\FeaturedProductResource\RelationManagers;
use App\Models\FeaturedProduct;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeaturedProductResource extends Resource
{
    protected static ?string $model = FeaturedProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $navigationLabel = 'Featured Products';

    protected static ?string $modelLabel = 'Featured Product';

    protected static ?string $pluralModelLabel = 'Featured Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Product Selection')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Select Product')
                            ->options(Product::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->helperText('Choose the product you want to feature')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $product = Product::find($state);
                                    if ($product && !$set('title')) {
                                        $set('title', $product->name);
                                    }
                                }
                            }),
                    ]),

                Forms\Components\Section::make('Custom Content (Optional)')
                    ->description('Override product details with custom content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Custom Title')
                            ->helperText('Leave empty to use product name')
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('featured_description')
                            ->label('Featured Description')
                            ->rows(3)
                            ->helperText('Custom description for featured display'),
                        
                        Forms\Components\TextInput::make('highlight_text')
                            ->label('Highlight Text')
                            ->placeholder('e.g., BESTSELLER, LIMITED TIME, NEW ARRIVAL')
                            ->maxLength(255)
                            ->helperText('Badge text to highlight the product'),
                        
                        Forms\Components\FileUpload::make('image')
                            ->label('Custom Image')
                            ->image()
                            ->directory('featured-products')
                            ->helperText('Override product image (optional)'),
                    ])->columns(2),

                Forms\Components\Section::make('Display Options')
                    ->schema([
                        Forms\Components\Toggle::make('show_price')
                            ->label('Show Price')
                            ->default(true),
                        
                        Forms\Components\Toggle::make('show_discount')
                            ->label('Show Discount')
                            ->default(true),
                        
                        Forms\Components\TextInput::make('icon')
                            ->label('Font Awesome Icon (Optional)')
                            ->placeholder('e.g., fas fa-fire')
                            ->helperText('Icon to display instead of product image')
                            ->maxLength(255),
                    ])->columns(3),

                Forms\Components\Section::make('Action Settings')
                    ->schema([
                        Forms\Components\TextInput::make('button_text')
                            ->label('Button Text')
                            ->placeholder('e.g., View Product, Add to Cart')
                            ->default('View Product')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('button_url')
                            ->label('Button URL')
                            ->placeholder('Leave empty to auto-generate product URL')
                            ->url()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Display Settings')
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        
                        Forms\Components\ColorPicker::make('background_color')
                            ->label('Background Color')
                            ->default('#8B5CF6'),
                        
                        Forms\Components\ColorPicker::make('text_color')
                            ->label('Text Color')
                            ->default('#FFFFFF'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('display_title')
                    ->label('Display Title')
                    ->getStateUsing(function ($record) {
                        return $record->display_title;
                    }),
                
                Tables\Columns\TextColumn::make('highlight_text')
                    ->label('Highlight')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\ImageColumn::make('display_image')
                    ->label('Image')
                    ->getStateUsing(function ($record) {
                        return $record->display_image;
                    })
                    ->size(50),
                
                Tables\Columns\IconColumn::make('show_price')
                    ->boolean()
                    ->label('Price'),
                
                Tables\Columns\IconColumn::make('show_discount')
                    ->boolean()
                    ->label('Discount'),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->label('Order'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_id')
                    ->label('Product')
                    ->options(Product::all()->pluck('name', 'id')),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
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
            'index' => Pages\ListFeaturedProducts::route('/'),
            'create' => Pages\CreateFeaturedProduct::route('/create'),
            'edit' => Pages\EditFeaturedProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
