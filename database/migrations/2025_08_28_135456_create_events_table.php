<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->date('event_date');
            $table->string('venue', 255)->nullable();
            $table->string('theme', 120)->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->unsignedInteger('guests')->nullable();
            $table->enum('status', ['requested', 'approved', 'scheduled', 'completed', 'cancelled'])->default('requested');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
