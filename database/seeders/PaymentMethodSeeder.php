<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            'Bank Transfer',
            'M-Pesa',
            'Card',
            'Cash'
        ];

        foreach ($methods as $method) {
            PaymentMethod::create([
                'name' => $method
            ]);
        }
    }
}
