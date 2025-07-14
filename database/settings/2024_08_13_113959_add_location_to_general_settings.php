<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.location', 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1499.0237477642488!2d80.25825064307512!3d12.972704959543144!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1669961429635!5m2!1sen!2sin');
    }
};
