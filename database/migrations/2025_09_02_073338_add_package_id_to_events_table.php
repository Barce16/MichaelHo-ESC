<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('events') && ! Schema::hasColumn('events', 'package_id')) {
            Schema::table('events', function (Blueprint $table) {
                $table->foreignId('package_id')
                    ->nullable()
                    ->constrained('packages')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('events') && Schema::hasColumn('events', 'package_id')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropForeign(['package_id']);
                $table->dropColumn('package_id');
            });
        }
    }
};
