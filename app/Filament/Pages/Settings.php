<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;

class Settings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';
  
    protected static ?string $navigationGroup = 'Admin';

    protected static string $settings = GeneralSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Company Information')
                ->description('Basic company details and contact information')
                ->icon('heroicon-o-building-office')
                ->collapsible()
                ->schema([
                    TextInput::make('company_name')
                        ->label('Company Name')
                        ->required()
                        ->maxLength(255)
                        ->helperText('This will be displayed as your company logo'),

                    TextInput::make('company_address')
                        ->label('Company Address')
                        ->required()
                        ->maxLength(500),

                    TextInput::make('location')
                        ->label('Company Location')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Company Email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    
                    Repeater::make('mobile_number')
                        ->label('Contact Numbers')
                        ->schema([
                            TextInput::make('Name')
                                ->label('Contact Type')
                                ->placeholder('e.g., Order Confirmation')
                                ->required(),
                            TextInput::make('Number')
                                ->label('Phone Number')
                                ->placeholder('e.g., +91-1234567890')
                                ->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('Add Contact Number')
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['Name'] ?? null),
                ]),
            
            Section::make('Business Settings')
                ->description('Configure discounts, minimum order values, and delivery charges')
                ->icon('heroicon-o-cog')
                ->collapsible()
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('global_discount')
                                ->label('Global Discount (%)')
                                ->numeric()
                                ->suffix('%')
                                ->minValue(0)
                                ->maxValue(100)
                                ->required()
                                ->columnSpan(1),

                            TextInput::make('min_order_value')
                                ->label('Minimum Order Value')
                                ->numeric()
                                ->prefix('₹')
                                ->required()
                                ->columnSpan(1),

                            TextInput::make('delivery_charges')
                                ->label('Delivery Charges')
                                ->numeric()
                                ->prefix('₹')
                                ->required()
                                ->columnSpan(1),
                        ]),
                ]),
            
            Section::make('Website Branding')
                ->description('Upload your company logo and favicon')
                ->icon('heroicon-o-photo')
                ->collapsible()
                ->schema([
                    Grid::make(2)
                        ->schema([
                            FileUpload::make('company_logo')
                                ->label('Company Logo')
                                ->image()
                                ->directory('logos')
                                ->acceptedFileTypes([
                                    'image/jpeg',
                                    'image/jpg', 
                                    'image/png',
                                    'image/svg+xml',
                                    'image/webp'
                                ])
                                ->maxSize(2048) // 2MB
                                ->helperText('Upload your company logo (JPG, PNG, SVG, WebP - Max 2MB)')
                                ->columnSpan(1),

                            FileUpload::make('company_logo_light')
                                ->label('Light Version Logo')
                                ->image()
                                ->directory('logos')
                                ->acceptedFileTypes([
                                    'image/jpeg',
                                    'image/jpg', 
                                    'image/png',
                                    'image/svg+xml',
                                    'image/webp'
                                ])
                                ->maxSize(2048) // 2MB
                                ->helperText('Light version of your logo for dark backgrounds')
                                ->columnSpan(1),
                        ]),
                    
                    Grid::make(2)
                        ->schema([
                            FileUpload::make('favicon')
                                ->label('Favicon (32x32)')
                                ->image()
                                ->directory('favicons')
                                ->acceptedFileTypes([
                                    'image/png',
                                    'image/x-icon',
                                    'image/svg+xml'
                                ])
                                ->maxSize(1024) // 1MB
                                ->helperText('Upload your favicon (PNG, ICO, SVG - Max 1MB)')
                                ->columnSpan(1),

                            FileUpload::make('apple_touch_icon')
                                ->label('Apple Touch Icon (180x180)')
                                ->image()
                                ->directory('favicons')
                                ->acceptedFileTypes([
                                    'image/png',
                                    'image/jpeg',
                                    'image/jpg'
                                ])
                                ->maxSize(1024) // 1MB
                                ->helperText('Icon for Apple devices (PNG, JPG - Max 1MB)')
                                ->columnSpan(1),
                        ]),
                    
                    Placeholder::make('favicon_info')
                        ->label('Favicon Information')
                        ->content('
                            <div class="text-sm text-gray-600">
                                <p><strong>Recommended sizes:</strong></p>
                                <ul class="list-disc list-inside mt-2">
                                    <li>Favicon: 32x32 pixels (PNG, ICO, or SVG)</li>
                                    <li>Apple Touch Icon: 180x180 pixels (PNG)</li>
                                    <li>Logo: 180x60 pixels (PNG, JPG, SVG, or WebP)</li>
                                </ul>
                                <p class="mt-2"><strong>Note:</strong> After uploading, clear your browser cache to see the new favicon.</p>
                            </div>
                        ')
                        ->columnSpan(2),
                ]),
        ]);
    }
}
