<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getTitle(): string
    {
        try {
            $settings = app(GeneralSettings::class);
            $companyName = $settings->company_name ?? ' Crackers';
            return "{$companyName} Dashboard";
        } catch (\Exception $e) {
            return ' Crackers Dashboard';
        }
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Add any header widgets here
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Add any footer widgets here
        ];
    }
} 