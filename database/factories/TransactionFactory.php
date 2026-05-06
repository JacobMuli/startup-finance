<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-1 year', 'now');
        $type = $this->faker->randomElement(['IN', 'OUT']);
        
        return [
            'transaction_id' => $this->faker->unique()->bothify('TXN-####-####'),
            'date' => $date->format('Y-m-d'),
            'month' => $date->format('F Y'),
            'month_date' => $date->format('Y-m-01'),
            'type' => $type,
            'category_id' => Category::factory(),
            'account_id' => Account::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'source_vendor' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'recorded_by' => 'system',
        ];
    }
}
