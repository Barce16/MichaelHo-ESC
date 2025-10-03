<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            // Add new type column
            $table->string('type', 50)->nullable()->after('slug');
        });

        // Migrate existing data if needed
        DB::table('packages')->update(['type' => 'wedding']); // or leave null

        // Drop description column
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->text('description')->nullable()->after('slug');
            $table->dropColumn('type');
        });
    }
};
