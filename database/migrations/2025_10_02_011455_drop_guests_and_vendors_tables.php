<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop pivot tables first (foreign key constraints)
        Schema::dropIfExists('package_vendor');
        Schema::dropIfExists('event_vendor');

        // Then drop main tables
        Schema::dropIfExists('guests');
        Schema::dropIfExists('vendors');
    }

    public function down(): void
    {
        // Recreate vendors table
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('contact_person', 150)->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('category', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 60)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // Recreate guests table
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->unsignedInteger('party_size')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        // Recreate pivot tables
        Schema::create('package_vendor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->decimal('price_override', 12, 2)->nullable();
            $table->timestamps();
            $table->unique(['package_id', 'vendor_id']);
        });

        Schema::create('event_vendor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['event_id', 'vendor_id']);
        });
    }
};
