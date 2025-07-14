<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Add logo settings to general settings
        $this->migrator->add('general.company_logo', null);
        $this->migrator->add('general.company_logo_light', null);
        $this->migrator->add('general.company_tagline', null);
        $this->migrator->add('general.logo_width', 180);
        $this->migrator->add('general.logo_height', 60);
    }
};
