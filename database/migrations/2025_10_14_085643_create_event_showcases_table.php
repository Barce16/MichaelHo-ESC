<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_showcases', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Wedding, Birthday, Corporate, etc.
            $table->string('event_name');
            $table->text('description'); // Quote or subtitle
            $table->string('location');
            $table->string('image_path');
            $table->boolean('is_published')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index(['is_published', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_showcases');
    }
};
