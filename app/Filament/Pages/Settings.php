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
        ]);
    }
}
