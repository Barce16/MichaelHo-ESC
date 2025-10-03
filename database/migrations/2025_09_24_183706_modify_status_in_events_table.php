<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('status', ['requested', 'approved', 'scheduled', 'completed', 'cancelled', 'request_meeting'])->default('requested')->change();
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('status', ['requested', 'approved', 'scheduled', 'completed', 'cancelled'])->default('requested')->change();
        });
    }
};
