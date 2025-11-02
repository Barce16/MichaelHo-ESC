<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('billings', function (Blueprint $table) {
            if (!Schema::hasColumn('billings', 'introductory_payment_amount')) {
                $table->decimal('introductory_payment_amount', 10, 2)->default(5000)->after('downpayment_amount');
            }

            if (!Schema::hasColumn('billings', 'introductory_payment_status')) {
                $table->string('introductory_payment_status')->default('pending')->after('introductory_payment_amount');
            }

            if (!Schema::hasColumn('billings', 'introductory_paid_at')) {
                $table->timestamp('introductory_paid_at')->nullable()->after('introductory_payment_status');
            }
        });

        // Add index separately to avoid issues with column positioning
        Schema::table('billings', function (Blueprint $table) {
            if (!$this->indexExists('billings', 'billings_introductory_payment_status_index')) {
                $table->index('introductory_payment_status');
            }
        });
    }

    public function down()
    {
        Schema::table('billings', function (Blueprint $table) {
            if ($this->indexExists('billings', 'billings_introductory_payment_status_index')) {
                $table->dropIndex(['introductory_payment_status']);
            }
        });

        Schema::table('billings', function (Blueprint $table) {
            $columns = ['introductory_payment_amount', 'introductory_payment_status', 'introductory_paid_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('billings', $column)) {
                    $table->dropColumn($column);
                }
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
