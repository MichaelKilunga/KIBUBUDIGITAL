<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            // Banks
            ['name' => 'NMB', 'type' => 'bank', 'account_number' => '24810029701'],
            ['name' => 'CRDB', 'type' => 'bank', 'account_number' => '015c0005pqw00'],
            ['name' => 'EQUITY', 'type' => 'bank', 'account_number' => '3007211942676'],
            ['name' => 'AZANIA', 'type' => 'bank', 'account_number' => '010000230631'],
            
            // Mobile Money
            [
                'name' => 'LIPA VODA', 
                'type' => 'mobile_money', 
                'account_number' => '68111310', 
                'ussd_string' => '*150*00*1*1*68111310#'
            ],
            [
                'name' => 'M-PESA', 
                'type' => 'mobile_money', 
                'account_number' => '0768571613', 
                'ussd_string' => '*150*00*1*1*0768571613#' // Assuming standard Lipa kwa M-Pesa format if it's a number
            ],
            [
                'name' => 'NBC', 
                'type' => 'mobile_money', 
                'account_number' => '074172000439', 
                'ussd_string' => '*150*11*1*1*074172000439#' // Generic NBC mobile USSD format
            ],
            [
                'name' => 'YAS', 
                'type' => 'mobile_money', 
                'account_number' => '0711XXXXXX', // Placeholder if not provided
                'ussd_string' => '*150*00#' // Generic placeholder
            ],
        ];

        foreach ($providers as $provider) {
            \App\Models\PaymentProvider::create($provider);
        }
    }
}
