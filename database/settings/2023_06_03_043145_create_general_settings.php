<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.global_discount', 50);
        $this->migrator->add('general.min_order_value', 2000);
        $this->migrator->add('general.company_name', 'Rajamanis pyrotech');
                    $this->migrator->add('general.email', 'info@vairamcrackers.com');
        $this->migrator->add('general.company_address', '1 st ');
        $this->migrator->add('general.mobile_number', [
            ['Name' => 'Order Confirmation', 'Number' => '9677879734'],
            ['Name' => ' Payment Confirmation', 'Number' => '9677879734'],
            ['Name' => 'Complaint', 'Number' => '9677879734']
        ]);
    }
}