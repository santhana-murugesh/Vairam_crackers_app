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
    
    // Logo settings
    public ?string $company_logo = null;
    public ?string $company_logo_light = null;
    public ?string $company_tagline = null;
    public int $logo_width = 180;
    public int $logo_height = 60;
    
    // Favicon settings
    public ?string $favicon = null;
    public ?string $favicon_32x32 = null;
    public ?string $favicon_16x16 = null;
    public ?string $apple_touch_icon = null;

    public static function group(): string
    {
        return 'general';
    }
    
    /**
     * Get the favicon URL
     */
    public function getFaviconUrl(): ?string
    {
        return $this->favicon ? asset('storage/' . $this->favicon) : null;
    }
    
    /**
     * Get the Apple touch icon URL
     */
    public function getAppleTouchIconUrl(): ?string
    {
        return $this->apple_touch_icon ? asset('storage/' . $this->apple_touch_icon) : null;
    }
    
    /**
     * Get the company logo URL
     */
    public function getCompanyLogoUrl(): ?string
    {
        return $this->company_logo ? asset('storage/' . $this->company_logo) : null;
    }
    
    /**
     * Get the light company logo URL
     */
    public function getCompanyLogoLightUrl(): ?string
    {
        return $this->company_logo_light ? asset('storage/' . $this->company_logo_light) : null;
    }
}

