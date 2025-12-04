<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->string('category')->nullable(); // e.g., 'transportation', 'materials', 'labor', 'miscellaneous'
            $table->date('expense_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('receipt_image')->nullable(); // optional receipt photo
            $table->timestamps();

            $table->index(['event_id', 'expense_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_expenses');
    }
};
