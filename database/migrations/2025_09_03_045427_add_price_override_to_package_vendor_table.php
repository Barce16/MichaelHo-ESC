<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('package_vendor', function (Blueprint $table) {
            if (!Schema::hasColumn('package_vendor', 'price_override')) {
                $table->decimal('price_override', 12, 2)->nullable()->after('vendor_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('package_vendor', function (Blueprint $table) {
            if (Schema::hasColumn('package_vendor', 'price_override')) {
                $table->dropColumn('price_override');
            }
        });
    }
};
