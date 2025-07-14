<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';
    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationLabel = 'Slider';
    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
                ->schema([
                    FileUpload::make('image')
                    ->label('Slider Image')
                    ->directory('sliders')
                    ->acceptedFileTypes([
                        'image/jpeg',
                        'image/jpg', 
                        'image/png',
                        'image/gif',
                        'image/svg+xml',
                        'image/webp',
                        'image/avif',
                        'image/bmp',
                        'image/tiff',
                        'image/tif',
                        'image/ico',
                        'image/heic',
                        'image/heif'
                    ])
                    ->maxSize(10240) // 10MB max size
                    ->imageEditor()
                    ->imagePreviewHeight('200')
                    ->loadingIndicatorPosition('center')
                    ->panelAspectRatio('16:9')
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadButtonPosition('left')
                    ->uploadProgressIndicatorPosition('left')
                    ->helperText('Supported formats: JPG, PNG, GIF, SVG, WebP, AVIF, BMP, TIFF, ICO, HEIC, HEIF (Max: 10MB)')
                    ->rules([
                        'mimes:jpeg,jpg,png,gif,svg,webp,avif,bmp,tiff,tif,ico,heic,heif',
                        'max:10240' // 10MB
                    ])
                    ->required(),
                    
                    Forms\Components\Textarea::make('content')
                        ->label('Slider Content')
                        ->rows(3)
                        ->placeholder('Enter slider content/description'),
                    
                    Forms\Components\TextInput::make('button_text')
                        ->label('Button Text')
                        ->placeholder('e.g., Shop Now, Learn More')
                        ->maxLength(255),
                        
                    // FileUpload::make('slider_image')->image()
                    // ->multiple()
                    // ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('content')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('button_text')
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
