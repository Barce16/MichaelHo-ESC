<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_staff', function (Blueprint $table) {
            $table->decimal('pay_rate', 10, 2)->nullable()->after('assignment_role');
            $table->string('pay_status', 20)->default('pending')->after('pay_rate');
        });
    }
    public function down(): void
    {
        Schema::table('event_staff', function (Blueprint $table) {
            $table->dropColumn(['pay_rate', 'pay_status']);
        });
    }
};
