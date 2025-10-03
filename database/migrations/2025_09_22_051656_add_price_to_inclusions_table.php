<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inclusions', function (Blueprint $table) {
            if (!Schema::hasColumn('inclusions', 'price')) {
                $table->decimal('price', 12, 2)->default(0)->after('name');
            }
        });
    }
    public function down(): void
    {
        Schema::table('inclusions', function (Blueprint $table) {
            $table->dropColumn(['price']);
        });
    }
};
