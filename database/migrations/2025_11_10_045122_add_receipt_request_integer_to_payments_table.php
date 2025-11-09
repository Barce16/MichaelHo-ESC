<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if column exists
        $hasReceiptRequest = Schema::hasColumn('payments', 'receipt_request');

        if ($hasReceiptRequest) {
            // Column exists - check if it's boolean and convert to integer
            $columnType = DB::select("
                SELECT DATA_TYPE 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_NAME = 'payments' 
                AND COLUMN_NAME = 'receipt_request' 
                AND TABLE_SCHEMA = DATABASE()
            ");

            if (!empty($columnType) && $columnType[0]->DATA_TYPE === 'tinyint') {
                // Already correct type - check if we need to add other columns
                if (!Schema::hasColumn('payments', 'receipt_created_at')) {
                    Schema::table('payments', function (Blueprint $table) {
                        $table->timestamp('receipt_created_at')->nullable()->after('receipt_requested_at');
                    });
                }
                return; // Column already exists with correct type
            }

            // Drop and recreate with correct type
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn(['receipt_request', 'receipt_requested_at']);
            });
        }

        // Create columns
        Schema::table('payments', function (Blueprint $table) {
            // 0 = not requested, 1 = requested, 2 = receipt created/ready
            $table->tinyInteger('receipt_request')->default(0)->after('status');
            $table->timestamp('receipt_requested_at')->nullable()->after('receipt_request');
            $table->timestamp('receipt_created_at')->nullable()->after('receipt_requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['receipt_request', 'receipt_requested_at', 'receipt_created_at']);
        });
    }
};
