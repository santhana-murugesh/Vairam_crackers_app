<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.global_discount', 50);
        $this->migrator->add('general.min_order_value', 2000);
        $this->migrator->add('general.company_name', 'ps');
                    $this->migrator->add('general.email', 'info@vairamcrackers.com');
        $this->migrator->add('general.company_address', '1 st ');
        $this->migrator->add('general.mobile_number', [
            ['Name' => 'Order Confirmation', 'Number' => '93843 57572,  80569 03599, 73971 53601, 97504 44600, 75388 97572'],
            ['Name' => ' Payment Confirmation', 'Number' => '97905 72926, 97900 39111, 97900 25111, 63854 27540, 63854 27539'],
            ['Name' => 'Complaint', 'Number' => '9942449196, 7540011846, 7530027560, 7530034370']
        ]);
    }
}