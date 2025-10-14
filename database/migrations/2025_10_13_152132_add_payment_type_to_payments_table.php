<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'payment_type')) {
                $table->string('payment_type', 50)->default('introductory')->after('billing_id');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (!$this->indexExists('payments', 'payments_payment_type_index')) {
                $table->index('payment_type');
            }
        });

        // Only update if the column was just added or needs updating
        if (Schema::hasColumn('payments', 'payment_type')) {
            DB::table('payments')
                ->whereNull('payment_type')
                ->orWhere('payment_type', '')
                ->update(['payment_type' => 'downpayment']);
        }
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            if ($this->indexExists('payments', 'payments_payment_type_index')) {
                $table->dropIndex(['payment_type']);
            }

            if (Schema::hasColumn('payments', 'payment_type')) {
                $table->dropColumn('payment_type');
            }
        });
    }

    private function indexExists($table, $index)
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();

        $result = $connection->select(
            "SELECT COUNT(*) as count FROM information_schema.statistics 
             WHERE table_schema = ? AND table_name = ? AND index_name = ?",
            [$database, $table, $index]
        );

        return $result[0]->count > 0;
    }
};
