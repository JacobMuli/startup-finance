<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $expenses = [
            'Hosting',
            'Domains',
            'Marketing',
            'Software Tools',
            'Transport',
            'Equipment',
            'Legal',
            'Operations',
            'Office Supplies'
        ];

        foreach ($expenses as $item) {
            Category::create([
                'name' => $item,
                'type' => 'OUT'
            ]);
        }

        $revenue = [
            'SaaS Subscription',
            'Consulting',
            'Implementation',
            'Training',
            'Grants',
            'Investment',
            'Data Services'
        ];

        foreach ($revenue as $item) {
            Category::create([
                'name' => $item,
                'type' => 'IN'
            ]);
        }
    }
}
