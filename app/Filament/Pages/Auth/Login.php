<?php

namespace App\Filament\Pages\Auth;

use App\Settings\GeneralSettings;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Components\Component;

class Login extends BaseLogin
{
    public function getTitle(): string
    {
        try {
            $settings = app(GeneralSettings::class);
            return $settings->company_name ?? '
             Crackers';
        } catch (\Exception $e) {
            return '
             Crackers';
        }
    }

    public function getHeading(): string
    {
        try {
            $settings = app(GeneralSettings::class);
            $companyName = $settings->company_name ?? '
             Crackers';
            return "Welcome to {$companyName}";
        } catch (\Exception $e) {
            return 'Welcome to 
             Crackers';
        }
    }

    public function getSubheading(): string
    {
        return 'Sign in to your account to continue.';
    }
} 