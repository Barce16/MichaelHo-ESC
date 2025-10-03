<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if guest_count column exists
        if (Schema::hasColumn('events', 'guest_count')) {
            Schema::table('events', function (Blueprint $table) {
                $table->text('guests')->nullable()->after('budget');
            });

            // Migrate data
            DB::statement("UPDATE events SET guests = CONCAT(guest_count, ' guests') WHERE guest_count IS NOT NULL");

            // Drop old column
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('guest_count');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('events', 'guests')) {
            Schema::table('events', function (Blueprint $table) {
                $table->unsignedInteger('guest_count')->nullable()->after('budget');
            });

            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('guests');
            });
        }
    }
};
