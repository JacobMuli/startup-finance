<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a transaction can be created and ID is generated.
     */
    public function test_transaction_can_be_created()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Software', 'type' => 'OUT']);
        $account = Account::create(['name' => 'Bank', 'type' => 'bank']);
        $method = PaymentMethod::create(['name' => 'Card']);

        $response = $this->actingAs($user)->post('/transactions', [
            'type' => 'OUT',
            'category_id' => $category->id,
            'amount' => 100.50,
            'date' => '2026-04-02',
            'account_id' => $account->id,
            'payment_method_id' => $method->id,
            'source_vendor' => 'AWS',
            'description' => 'EC2 Instance',
        ]);

        $response->assertRedirect('/transactions');
        $this->assertDatabaseHas('transactions', [
            'amount' => 100.50,
            'source_vendor' => 'AWS',
            'month' => 'April 2026',
        ]);

        $transaction = \App\Models\Transaction::first();
        $this->assertStringStartsWith('TXN-2026-', $transaction->transaction_id);
    }

    /**
     * Test Transaction ID increments correctly.
     */
    public function test_transaction_id_increments()
    {
        $year = now()->year;
        
        $id1 = TransactionService::generateTransactionId();
        $this->assertEquals("TXN-{$year}-0001", $id1);

        \App\Models\Transaction::create([
            'transaction_id' => $id1,
            'date' => now(),
            'month' => now()->format('F Y'),
            'type' => 'IN',
            'category_id' => Category::create(['name' => 'SaaS', 'type' => 'IN'])->id,
            'amount' => 100,
        ]);

        $id2 = TransactionService::generateTransactionId();
        $this->assertEquals("TXN-{$year}-0002", $id2);
    }

    /**
     * Test that a transaction cannot be created with a mismatched category type.
     */
    public function test_transaction_category_type_mismatch_fails()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Software', 'type' => 'OUT']);

        $response = $this->actingAs($user)->from('/transactions/create')->post('/transactions', [
            'type' => 'IN', // Mismatch!
            'category_id' => $category->id,
            'amount' => 100.50,
            'date' => '2026-04-02',
        ]);

        $response->assertRedirect('/transactions/create');
        $response->assertSessionHasErrors(['category_id']);
        $this->assertEquals(0, \App\Models\Transaction::count());
    }
}
