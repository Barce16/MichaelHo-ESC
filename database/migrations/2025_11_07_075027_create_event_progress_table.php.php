<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->text('details')->nullable();
            $table->timestamp('progress_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_progress');
    }
};
