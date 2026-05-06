<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReceiptUploadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a transaction can be created with a receipt upload.
     */
    public function test_transaction_can_be_created_with_receipt()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Software', 'type' => 'OUT']);

        $file = UploadedFile::fake()->image('receipt.jpg');

        $response = $this->actingAs($user)->post('/transactions', [
            'type' => 'OUT',
            'category_id' => $category->id,
            'amount' => 100.50,
            'date' => '2026-04-02',
            'receipt_file' => $file,
        ]);

        $response->assertRedirect('/transactions');
        $this->assertDatabaseHas('transactions', [
            'amount' => 100.50,
        ]);

        $transaction = \App\Models\Transaction::first();
        $this->assertNotNull($transaction->receipt_id);
        
        $receipt = $transaction->receipt;
        $this->assertNotNull($receipt);
        
        // Assert file exists in structured path: receipts/YYYY/MM/TXN-ID-TIMESTAMP.extension
        $this->assertMatchesRegularExpression(
            '/^receipts\/\d{4}\/\d{2}\/TXN-\d{4}-\d{4}-\d+\.jpg$/',
            $receipt->file_path
        );
        
        Storage::disk('public')->assertExists($receipt->file_path);
    }

    /**
     * Test invalid file type upload fails.
     */
    public function test_invalid_receipt_file_type_fails()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Software', 'type' => 'OUT']);

        $file = UploadedFile::fake()->create('document.txt', 100);

        $response = $this->actingAs($user)->from('/transactions/create')->post('/transactions', [
            'type' => 'OUT',
            'category_id' => $category->id,
            'amount' => 100.50,
            'date' => '2026-04-02',
            'receipt_file' => $file,
        ]);

        $response->assertRedirect('/transactions/create');
        $response->assertSessionHasErrors(['receipt_file']);
        $this->assertEquals(0, \App\Models\Transaction::count());
    }

    /**
     * Test file size limit (2MB) fails.
     */
    public function test_large_receipt_file_fails()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Software', 'type' => 'OUT']);

        $file = UploadedFile::fake()->create('large_receipt.pdf', 3000); // 3MB

        $response = $this->actingAs($user)->from('/transactions/create')->post('/transactions', [
            'type' => 'OUT',
            'category_id' => $category->id,
            'amount' => 100.50,
            'date' => '2026-04-02',
            'receipt_file' => $file,
        ]);

        $response->assertRedirect('/transactions/create');
        $response->assertSessionHasErrors(['receipt_file']);
    }

    /**
     * Test that deleting a transaction also deletes the receipt file.
     */
    public function test_file_is_deleted_when_transaction_is_deleted()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Software', 'type' => 'OUT']);

        $file = UploadedFile::fake()->image('receipt.jpg');

        $this->actingAs($user)->post('/transactions', [
            'type' => 'OUT',
            'category_id' => $category->id,
            'amount' => 100.50,
            'date' => '2026-04-02',
            'receipt_file' => $file,
        ]);

        $transaction = \App\Models\Transaction::first();
        $path = $transaction->receipt->file_path;
        Storage::disk('public')->assertExists($path);

        // Delete transaction
        $transaction->delete();

        // Assert file is deleted from disk
        Storage::disk('public')->assertMissing($path);
        
        // Assert receipt record is deleted
        $this->assertDatabaseMissing('receipts', ['file_path' => $path]);
    }

    /**
     * Test filename is unique with timestamp.
     */
    public function test_filename_is_unique_with_timestamp()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Software', 'type' => 'OUT']);

        $file = UploadedFile::fake()->image('receipt.jpg');

        $this->actingAs($user)->post('/transactions', [
            'type' => 'OUT',
            'category_id' => $category->id,
            'amount' => 100.50,
            'date' => '2026-04-02',
            'receipt_file' => $file,
        ]);

        $transaction = \App\Models\Transaction::first();
        $this->assertMatchesRegularExpression(
            '/^receipts\/\d{4}\/\d{2}\/TXN-\d{4}-\d{4}-\d+\.jpg$/',
            $transaction->receipt->file_path
        );
    }
}
