<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public int $global_discount;

    public int $min_order_value;
    public string $company_name;
    public string $company_address;
    public string $email;
    public array $mobile_number = [];
    public string $location;
    public string $delivery_charges;
    
    // Logo settings - using company name as logo
    public ?string $company_tagline = null;

    public static function group(): string
    {
        return 'general';
    }
}

