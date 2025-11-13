<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inclusion_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');

            // Store old and new inclusions as JSON with details
            $table->json('old_inclusions'); // [{id, name, price}, ...]
            $table->json('new_inclusions'); // [{id, name, price}, ...]
            $table->json('inclusion_notes')->nullable(); // {inclusion_id: "note", ...}

            // Billing info
            $table->decimal('old_total', 10, 2);
            $table->decimal('new_total', 10, 2);
            $table->decimal('difference', 10, 2);

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();

            // Approval tracking
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['event_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inclusion_change_requests');
    }
};
