<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->integer('serial_no')->nullable();
            $table->string('transaction_id')->unique();

            $table->date('date');
            $table->string('month');

            $table->enum('type', ['IN', 'OUT']);

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->string('department')->nullable();
            $table->string('source_vendor')->nullable();
            $table->text('description')->nullable();

            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('account_id')->nullable()->constrained()->nullOnDelete();

            $table->decimal('amount', 15, 2);

            $table->foreignId('receipt_id')->nullable()->constrained()->nullOnDelete();
            $table->text('receipt_link')->nullable();

            $table->string('approved_by')->nullable();
            $table->string('recorded_by')->nullable();

            $table->string('transaction_tag')->nullable();

            $table->index('date');
            $table->index('type');
            $table->index('category_id');
            $table->index('account_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
