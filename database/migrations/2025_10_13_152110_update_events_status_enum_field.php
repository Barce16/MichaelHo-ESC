<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, update any existing statuses that might conflict
        DB::table('events')->where('status', 'cancelled')->update(['status' => 'rejected']);

        // Modify the ENUM to include all new statuses
        DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM(
            'requested',
            'approved', 
            'request_meeting',
            'meeting',
            'scheduled',
            'ongoing',
            'completed',
            'rejected'
        ) DEFAULT 'requested'");

        // Add index for better performance
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasIndex('events', ['status'])) {
                $table->index('status');
            }
        });
    }

    public function down()
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM(
            'requested',
            'approved',
            'confirmed',
            'completed',
            'cancelled'
        ) DEFAULT 'requested'");

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
