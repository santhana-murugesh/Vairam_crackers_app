<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Add favicon settings to general settings
        $this->migrator->add('general.favicon', null);
        $this->migrator->add('general.favicon_32x32', null);
        $this->migrator->add('general.favicon_16x16', null);
        $this->migrator->add('general.apple_touch_icon', null);
    }

    public function down(): void
    {
        // Remove favicon settings from general settings
        $this->migrator->delete('general.favicon');
        $this->migrator->delete('general.favicon_32x32');
        $this->migrator->delete('general.favicon_16x16');
        $this->migrator->delete('general.apple_touch_icon');
    }
};
