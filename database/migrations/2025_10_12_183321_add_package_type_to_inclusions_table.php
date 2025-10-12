<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inclusions', function (Blueprint $table) {
            $table->string('package_type')->nullable()->after('category');
            $table->index('package_type'); // For faster queries
        });
    }

    public function down()
    {
        Schema::table('inclusions', function (Blueprint $table) {
            $table->dropIndex(['package_type']);
            $table->dropColumn('package_type');
        });
    }
};
