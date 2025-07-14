<?php
namespace App\Filament\Pages;

use Filament\Pages\Page;

class GenerateBilling extends Page
{
    public static ?string $title = 'Billings';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    
    protected static ?string $navigationGroup = 'Billings';

    protected static ?string $navigationLabel = 'Billing';
    
    protected static ?int $navigationSort = 1;

      // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::count();
    // }

    protected static string $view = 'filament.pages.billings';
}
