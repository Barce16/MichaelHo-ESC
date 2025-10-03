<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateEventStatusEnum extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE `events` MODIFY `status` ENUM('requested', 'approved', 'meeting', 'scheduled', 'completed', 'rejected', 'request_meeting') NOT NULL DEFAULT 'requested'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `events` MODIFY `status` ENUM('requested', 'approved', 'scheduled', 'completed', 'rejected', 'request_meeting') NOT NULL DEFAULT 'requested'");
    }
}
