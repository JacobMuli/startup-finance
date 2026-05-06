<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilteringTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that transactions can be filtered by type.
     */
    public function test_transactions_can_be_filtered_by_type()
    {
        $user = User::factory()->create();
        $user->markEmailAsVerified();
        Transaction::factory()->count(3)->create(['type' => 'IN']);
        Transaction::factory()->count(2)->create(['type' => 'OUT']);

        $response = $this->actingAs($user)->get('/transactions?type=IN');

        $response->assertStatus(200);
        $response->assertViewHas('transactions', function ($transactions) {
            return $transactions->count() === 3 && $transactions->where('type', 'OUT')->count() === 0;
        });
    }

    /**
     * Test that transactions can be filtered by date range.
     */
    public function test_transactions_can_be_filtered_by_date_range()
    {
        $user = User::factory()->create();
        $user->markEmailAsVerified();
        Transaction::factory()->create(['date' => '2026-01-15']);
        Transaction::factory()->create(['date' => '2026-02-15']);
        Transaction::factory()->create(['date' => '2026-03-15']);

        $response = $this->actingAs($user)->get('/transactions?from_date=2026-01-01&to_date=2026-02-28');

        $response->assertStatus(200);
        $response->assertViewHas('transactions', function ($transactions) {
            return $transactions->count() === 2;
        });
    }

    /**
     * Test search functionality (keyword search).
     */
    public function test_transactions_can_be_filtered_by_search_keyword()
    {
        $user = User::factory()->create();
        $user->markEmailAsVerified();
        Transaction::factory()->create(['source_vendor' => 'Amazon AWS', 'description' => 'Server hosting']);
        Transaction::factory()->create(['source_vendor' => 'Google Cloud', 'description' => 'Storage']);
        Transaction::factory()->create(['source_vendor' => 'Starbucks', 'description' => 'Coffee for team']);

        $response = $this->actingAs($user)->get('/transactions?search=hosting');

        $response->assertStatus(200);
        $response->assertViewHas('transactions', function ($transactions) {
            return $transactions->count() === 1 && str_contains($transactions->first()->description, 'hosting');
        });
        
        $response = $this->actingAs($user)->get('/transactions?search=Google');
        $response->assertViewHas('transactions', function ($transactions) {
            return $transactions->count() === 1 && str_contains($transactions->first()->source_vendor, 'Google');
        });
    }

    /**
     * Test combined filters.
     */
    public function test_transactions_can_be_filtered_by_combined_criteria()
    {
        $user = User::factory()->create();
        $user->markEmailAsVerified();
        $account = Account::factory()->create(['name' => 'Main Account']);
        $category = Category::factory()->create(['name' => 'Hosting', 'type' => 'OUT']);
        
        Transaction::factory()->create([
            'type' => 'OUT', 
            'category_id' => $category->id,
            'account_id' => $account->id,
            'date' => '2026-04-01'
        ]);
        
        Transaction::factory()->create(['type' => 'IN']); // Noise

        $response = $this->actingAs($user)->get('/transactions?type=OUT&category_id='.$category->id.'&account_id='.$account->id);

        $response->assertStatus(200);
        $response->assertViewHas('transactions', function ($transactions) {
            return $transactions->count() === 1;
        });
    }

    /**
     * Test dashboard is filter-aware.
     */
    public function test_dashboard_is_filter_aware()
    {
        $user = User::factory()->create();
        $user->markEmailAsVerified();
        
        // Month 1
        Transaction::factory()->create(['type' => 'IN', 'amount' => 1000, 'date' => '2026-01-10']);
        // Month 2
        Transaction::factory()->create(['type' => 'IN', 'amount' => 2000, 'date' => '2026-02-10']);

        // Unfiltered dashboard
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertViewHas('totalRevenue', 3000);

        // Filtered dashboard (January only)
        $response = $this->actingAs($user)->get('/dashboard?from_date=2026-01-01&to_date=2026-01-31');
        $response->assertViewHas('totalRevenue', 1000);
    }
}
