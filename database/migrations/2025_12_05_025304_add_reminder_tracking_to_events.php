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
        Schema::table('events', function (Blueprint $table) {
            $table->timestamp('reminder_30days_sent_at')->nullable()->after('status');
            $table->timestamp('reminder_7days_sent_at')->nullable()->after('reminder_30days_sent_at');
            $table->timestamp('reminder_3days_sent_at')->nullable()->after('reminder_7days_sent_at');
            $table->timestamp('reminder_today_sent_at')->nullable()->after('reminder_3days_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'reminder_30days_sent_at',
                'reminder_7days_sent_at',
                'reminder_3days_sent_at',
                'reminder_today_sent_at',
            ]);
        });
    }
};
