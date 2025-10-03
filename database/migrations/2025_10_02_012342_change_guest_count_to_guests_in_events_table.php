<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if column exists before adding
        if (!Schema::hasColumn('events', 'guests')) {
            Schema::table('events', function (Blueprint $table) {
                $table->text('guests')->nullable()->after('budget');
            });
        }

        // Migrate existing data from guest_count to guests (only if guest_count exists)
        if (Schema::hasColumn('events', 'guest_count')) {
            DB::statement("UPDATE events SET guests = CONCAT(guest_count, ' guests') WHERE guest_count IS NOT NULL");

            // Drop old guest_count column
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('guest_count');
            });
        }
    }

    public function down(): void
    {
        // Check if guest_count doesn't exist before adding
        if (!Schema::hasColumn('events', 'guest_count')) {
            Schema::table('events', function (Blueprint $table) {
                $table->unsignedInteger('guest_count')->nullable()->after('budget');
            });
        }

        // Restore data if rolling back
        if (Schema::hasColumn('events', 'guests')) {
            DB::statement("UPDATE events SET guest_count = CAST(REGEXP_REPLACE(guests, '[^0-9]', '') AS UNSIGNED) WHERE guests IS NOT NULL");

            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('guests');
            });
        }
    }
};
