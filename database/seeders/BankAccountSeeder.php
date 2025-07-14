<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BankAccount;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bankAccounts = [
            [
                'bank_name' => 'State Bank of India',
                'branch' => 'Sivakasi Main Branch',
                'name' => 'Vairam Crackers',
                'account_number' => '1234567890123456',
                'ifsc_code' => 'SBIN0001234',
            ],
            [
                'bank_name' => 'HDFC Bank',
                'branch' => 'Sivakasi',
                'name' => 'Vairam Crackers',
                'account_number' => '9876543210987654',
                'ifsc_code' => 'HDFC0001567',
            ],
            [
                'bank_name' => 'ICICI Bank',
                'branch' => 'Sivakasi',
                'name' => 'Company Name',
                'account_number' => '5678901234567890',
                'ifsc_code' => 'ICIC0001890',
            ],
        ];

        foreach ($bankAccounts as $account) {
            BankAccount::create($account);
        }
    }
}
