<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_inclusion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inclusion_id')->constrained()->restrictOnDelete();
            $table->decimal('price_snapshot', 12, 2);
            $table->timestamps();

            $table->unique(['event_id', 'inclusion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_inclusion');
    }
};
