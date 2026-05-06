<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the dashboard loads for authenticated users.
     */
    public function test_dashboard_loads_for_authenticated_users()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Founder Dashboard');
        $response->assertSee('Total Revenue');
    }

    /**
     * Test metrics calculation on the dashboard.
     */
    public function test_dashboard_metrics_calculation()
    {
        $user = User::factory()->create();
        $inCategory = Category::create(['name' => 'SaaS', 'type' => 'IN']);
        $outCategory = Category::create(['name' => 'Hosting', 'type' => 'OUT']);

        // Revenue: 5000 + 1500 = 6500
        Transaction::create(['amount' => 5000, 'type' => 'IN', 'category_id' => $inCategory->id, 'date' => '2026-03-01', 'month' => 'March 2026', 'transaction_id' => 'TXN-001']);
        Transaction::create(['amount' => 1500, 'type' => 'IN', 'category_id' => $inCategory->id, 'date' => '2026-04-01', 'month' => 'April 2026', 'transaction_id' => 'TXN-002']);

        // Expenses: 2000
        Transaction::create(['amount' => 2000, 'type' => 'OUT', 'category_id' => $outCategory->id, 'date' => '2026-04-15', 'month' => 'April 2026', 'transaction_id' => 'TXN-003']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('6,500.00'); // Total Revenue
        $response->assertSee('2,000.00'); // Total Expenses
        $response->assertSee('4,500.00'); // Net Profit
    }

    /**
     * Test burn rate calculation (last 3 months average).
     */
    public function test_dashboard_burn_rate_calculation()
    {
        $user = User::factory()->create();
        $outCategory = Category::create(['name' => 'Hosting', 'type' => 'OUT']);

        // Expenses across 4 months: 3000, 2000, 1000, 500 (earliest)
        // Last 3 months average: (3000 + 2000 + 1000) / 3 = 2000
        Transaction::create(['amount' => 3000, 'type' => 'OUT', 'category_id' => $outCategory->id, 'date' => '2026-04-01', 'month' => 'April 2026', 'transaction_id' => 'TXN-001', 'created_at' => now()]);
        Transaction::create(['amount' => 2000, 'type' => 'OUT', 'category_id' => $outCategory->id, 'date' => '2026-03-01', 'month' => 'March 2026', 'transaction_id' => 'TXN-002', 'created_at' => now()->subMonth()]);
        Transaction::create(['amount' => 1000, 'type' => 'OUT', 'category_id' => $outCategory->id, 'date' => '2026-02-01', 'month' => 'February 2026', 'transaction_id' => 'TXN-003', 'created_at' => now()->subMonths(2)]);
        Transaction::create(['amount' => 500,  'type' => 'OUT', 'category_id' => $outCategory->id, 'date' => '2026-01-01', 'month' => 'January 2026', 'transaction_id' => 'TXN-004', 'created_at' => now()->subMonths(3)]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertSee('2,000.00'); // Average Burn Rate
    }

    /**
     * Test edge case: no transactions.
     */
    public function test_dashboard_loads_with_no_transactions()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('0.00');
        $response->assertSee('Infinite / No Burn');
    }
}
