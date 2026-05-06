<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles for each test
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'recorder']);
        Role::create(['name' => 'viewer']);
    }

    /**
     * Test that guests are redirected to login.
     */
    public function test_guests_cannot_access_transactions()
    {
        $response = $this->get('/transactions');
        $response->assertRedirect('/login');
    }

    /**
     * Test that 'Viewer' role cannot delete transactions.
     */
    public function test_viewers_cannot_delete_transactions()
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('viewer');
        $viewer->markEmailAsVerified();

        $transaction = Transaction::factory()->create();

        $response = $this->actingAs($viewer)->delete("/transactions/{$transaction->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('transactions', ['id' => $transaction->id]);
    }

    /**
     * Test that 'Admin' role can delete transactions and it is audited.
     */
    public function test_admins_can_delete_transactions_and_it_is_audited()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->markEmailAsVerified();

        $transaction = Transaction::factory()->create();

        $response = $this->actingAs($admin)->delete("/transactions/{$transaction->id}");

        $response->assertStatus(302);
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
        
        // Assert Audit Log exists
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'deleted',
            'model_type' => 'Transaction',
            'model_id' => $transaction->id,
            'user_id' => $admin->id
        ]);
    }

    /**
     * Test that receipts are not publicly accessible via direct URL.
     */
    public function test_receipts_are_protected()
    {
        $user = User::factory()->create();
        $user->markEmailAsVerified();
        
        $receipt = \App\Models\Receipt::factory()->create();
        $transaction = Transaction::factory()->create(['receipt_id' => $receipt->id]);
        
        // Ensure the file exists in private storage for the download test
        \Illuminate\Support\Facades\Storage::disk('local')->put($receipt->file_path, 'fake receipt content');
        
        // Direct storage/receipts link should not work (storage is private now)
        $publicUrl = "/storage/" . $transaction->receipt->file_path;
        $response = $this->get($publicUrl);
        // Depending on web server config, this might be 404 or 403 (forbidden)
        $this->assertTrue($response->isNotFound() || $response->isForbidden());

        // Secure route should work for authenticated user
        $response = $this->actingAs($user)->get(route('receipts.show', $transaction->receipt_id));
        $response->assertStatus(200);
    }

    public function test_transaction_creation_is_audited()
    {
        $user = User::factory()->create();
        $user->assignRole('recorder');
        $user->markEmailAsVerified();

        $transactionData = Transaction::factory()->make()->toArray();
        // Mimic a form submission
        $response = $this->actingAs($user)->post('/transactions', $transactionData);

        $response->assertStatus(302); // Ensure it didn't fail validation
        
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'created',
            'model_type' => 'Transaction',
            'user_id' => $user->id
        ]);
    }
}
