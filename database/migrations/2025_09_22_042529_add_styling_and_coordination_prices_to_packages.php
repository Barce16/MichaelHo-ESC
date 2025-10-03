<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('coordination_price', 12, 2)->default(25000)->after('coordination');
            $table->decimal('event_styling_price', 12, 2)->default(55000)->after('coordination_price');
        });
    }
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['coordination_price', 'event_styling_price']);
        });
    }
};
