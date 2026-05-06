<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin']);
    }

    /**
     * Test that transactions can be exported to CSV.
     */
    public function test_transactions_can_be_exported_to_csv()
    {
        $user = User::factory()->create();
        $user->markEmailAsVerified();
        $user->assignRole('admin');
        Transaction::factory()->count(5)->create();

        $response = $this->actingAs($user)->get('/transactions/export');

        $response->assertStatus(200);
        $this->assertStringContainsString('attachment; filename=transactions_', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        
        $content = $response->streamedContent();
        
        // Assert UTF-8 BOM is present
        $this->assertStringStartsWith("\xEF\xBB\xBF", $content);
        
        // Assert Headers exist (checking individually for robustness against formatting)
        $this->assertStringContainsString('Transaction ID', $content);
        $this->assertStringContainsString('Date', $content);
        $this->assertStringContainsString('Month', $content);
        $this->assertStringContainsString('Type', $content);
        $this->assertStringContainsString('Category', $content);
        $this->assertStringContainsString('Account', $content);
        $this->assertStringContainsString('Payment Method', $content);
        $this->assertStringContainsString('Vendor', $content);
        $this->assertStringContainsString('Description', $content);
        $this->assertStringContainsString('Amount', $content);
        
        // Assert some data exists (count lines, header + 5 rows = 6 lines minimum)
        $lines = explode("\n", trim($content));
        $this->assertGreaterThanOrEqual(6, count($lines));
    }

    /**
     * Test that export respects active filters.
     */
    public function test_export_respects_active_filters()
    {
        $user = User::factory()->create();
        $user->markEmailAsVerified();
        $user->assignRole('admin');
        $cat1 = Category::factory()->create(['name' => 'Software']);
        $cat2 = Category::factory()->create(['name' => 'Hardware']);
        
        Transaction::factory()->count(3)->create(['category_id' => $cat1->id]);
        Transaction::factory()->count(2)->create(['category_id' => $cat2->id]);

        $response = $this->actingAs($user)->get('/transactions/export?category_id=' . $cat1->id);

        $response->assertStatus(200);
        $content = $response->streamedContent();
        
        $this->assertStringContainsString('Software', $content);
        $this->assertStringNotContainsString('Hardware', $content);
        
        $lines = explode("\n", trim($content));
        // Header + 3 rows = 4 lines
        $this->assertEquals(4, count($lines));
    }

    /**
     * Test export with large dataset (Memory efficiency smoke test).
     */
    public function test_export_works_with_large_dataset()
    {
        $user = User::factory()->create();
        $user->markEmailAsVerified();
        $user->assignRole('admin');
        // Create 100 transactions
        Transaction::factory()->count(100)->create();

        $response = $this->actingAs($user)->get('/transactions/export');

        $response->assertStatus(200);
        $content = $response->streamedContent();
        $lines = explode("\n", trim($content));
        $this->assertEquals(101, count($lines)); // 100 rows + 1 header
    }

    /**
     * Test Monthly Report visibility and content.
     */
    public function test_monthly_report_displays_correct_aggregations()
    {
        $user = User::factory()->create();
        $user->markEmailAsVerified();
        $user->assignRole('admin');
        
        // January
        Transaction::factory()->create(['type' => 'IN', 'amount' => 1000, 'date' => '2026-01-10']);
        Transaction::factory()->create(['type' => 'OUT', 'amount' => 500, 'date' => '2026-01-15']);
        
        // February
        Transaction::factory()->create(['type' => 'IN', 'amount' => 2000, 'date' => '2026-02-10']);

        $response = $this->actingAs($user)->get('/reports/monthly');

        $response->assertStatus(200);
        $response->assertSee('January 2026');
        $response->assertSee('February 2026');
        
        // Check totals in view
        $response->assertSee('1,000.00');
        $response->assertSee('500.00');
        $response->assertSee('2,000.00');
    }
}
