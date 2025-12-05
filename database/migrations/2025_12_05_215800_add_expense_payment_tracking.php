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
        // Add payment tracking to event_expenses
        Schema::table('event_expenses', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid')->after('receipt_image');
            $table->timestamp('paid_at')->nullable()->after('payment_status');
        });

        // Add expense_id to payments table for linking expense payments
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('expense_id')->nullable()->after('billing_id')->constrained('event_expenses')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['expense_id']);
            $table->dropColumn('expense_id');
        });

        Schema::table('event_expenses', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'paid_at']);
        });
    }
};
