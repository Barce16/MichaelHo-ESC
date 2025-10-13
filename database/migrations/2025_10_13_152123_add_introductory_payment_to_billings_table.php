<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->decimal('introductory_payment_amount', 10, 2)->default(15000)->after('downpayment_amount');
            $table->string('introductory_payment_status')->default('pending')->after('introductory_payment_amount');
            $table->timestamp('introductory_paid_at')->nullable()->after('introductory_payment_status');

            // Add indexes
            $table->index('introductory_payment_status');
        });
    }

    public function down()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropIndex(['introductory_payment_status']);
            $table->dropColumn([
                'introductory_payment_amount',
                'introductory_payment_status',
                'introductory_paid_at'
            ]);
        });
    }
};
