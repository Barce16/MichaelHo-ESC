<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inclusions', function (Blueprint $table) {
            $table->string('contact_person')->nullable()->after('name');
            $table->string('contact_email')->nullable()->after('contact_person');
            $table->string('contact_phone')->nullable()->after('contact_email');
        });
    }

    public function down()
    {
        Schema::table('inclusions', function (Blueprint $table) {
            $table->dropColumn(['contact_person', 'contact_email', 'contact_phone']);
        });
    }
};
