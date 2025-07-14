<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            ['name' => 'Andhra Pradesh','code' => 'AD',],
            ['name' => 'Arunachal Pradesh','code' => 'AR',],
            ['name' => 'Assam','code' => 'AS',],
            ['name' => 'Bihar','code' => 'BR',],
            ['name' => 'Chattisgarh','code' => 'CG',],
            ['name' => 'Delhi','code' => 'DL',],
            ['name' => 'Goa','code' => 'GA',],
            ['name' => 'Gujarat','code' => 'GJ',],
            ['name' => 'Haryana','code' => 'HR',],
            ['name' => 'Himachal Pradesh','code' => 'HP',],
            ['name' => 'Jammu and Kashmir','code' => 'JK',],
            ['name' => 'Jharkhand','code' => 'JH',],
            ['name' => 'Karnataka','code' => 'KA',],
            ['name' => 'Kerala','code' => 'KL',],
            ['name' => 'Lakshadweep Islands','code' => 'LD',],
            ['name' => 'Madhya Pradesh','code' => 'MP',],
            ['name' => 'Maharashtra','code' => 'MH',],
            ['name' => 'Manipur','code' => 'MN',],
            ['name' => 'Meghalaya','code' => 'ML',],
            ['name' => 'Mizoram','code' => 'MZ',],
            ['name' => 'Nagaland','code' => 'NL',],
            ['name' => 'Odisha','code' => 'OD',],
            ['name' => 'Pondicherry','code' => 'PY',],
            ['name' => 'Punjab','code' => 'PB',],
            ['name' => 'Sikkim','code' => 'SK',],
            ['name' => 'Tamil Nadu','code' => 'TN',],
            ['name' => 'Telangana','code' => 'TS',],
            ['name' => 'Uttar Pradesh','code' => 'UP',],
            ['name' => 'Uttarakhand','code' => 'UK',],
            ['name' => 'West Bengal	','code' => 'WB',],
        ];

        foreach($states as $state){
            State::create($state);
        }
    }
}
