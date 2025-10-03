<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('package_vendor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->decimal('price_override', 12, 2)->nullable();
            $table->timestamps();
            $table->unique(['package_id', 'vendor_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('package_vendor');
    }
};
