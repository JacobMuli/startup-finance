<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['name' => 'Business Bank', 'type' => 'bank'],
            ['name' => 'M-Pesa Till', 'type' => 'mobile'],
            ['name' => 'Cash', 'type' => 'cash'],
            ['name' => 'Payment Gateway', 'type' => 'gateway'],
        ];

        foreach ($accounts as $acc) {
            Account::create($acc);
        }
    }
}
