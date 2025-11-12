<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_staff', function (Blueprint $table) {
            $table->enum('work_status', ['pending', 'ongoing', 'finished'])
                ->default('pending')
                ->after('pay_status')
                ->comment('pending = waiting for event day, ongoing = event day arrived, finished = work completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_staff', function (Blueprint $table) {
            $table->dropColumn('work_status');
        });
    }
};
