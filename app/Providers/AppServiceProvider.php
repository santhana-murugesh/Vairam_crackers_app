<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Inertia\Inertia;
use App\Settings\GeneralSettings;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::registerNavigationGroups([
            'Orders',
            'Categories',
            'Products',
            'Customers',
            'Admin',
        ]);

        // Share variables with all Inertia responses
        Inertia::share([
            'settings' => function () {
                $settings = app(GeneralSettings::class);
                return [
                    'global_discount' => $settings->global_discount,
                    'company_address' => $settings->company_address,
                    'company_name' => $settings->company_name,
                    'mobile_number' => $settings->mobile_number,
                    'email' => $settings->email,
                ];
            },
        ]);
    }
}
