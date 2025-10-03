<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('staffs', function (Blueprint $table) {
            $table->decimal('rate', 10, 2)->nullable()->after('role_type');
            $table->string('rate_type', 20)->nullable()->after('rate');
        });
    }
    public function down(): void
    {
        Schema::table('staffs', function (Blueprint $table) {
            $table->dropColumn(['rate', 'rate_type']);
        });
    }
};
