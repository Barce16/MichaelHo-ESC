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
        Schema::table('event_schedules', function (Blueprint $table) {
            $table->foreignId('staff_id')->nullable()->after('inclusion_id')->constrained('staffs')->nullOnDelete();
            $table->string('contact_number')->nullable()->after('remarks');
            $table->string('venue')->nullable()->after('contact_number');
            $table->string('proof_image')->nullable()->after('venue'); // Proof uploaded by staff
            $table->timestamp('proof_uploaded_at')->nullable()->after('proof_image');
            $table->timestamp('notified_at')->nullable()->after('proof_uploaded_at'); // Track when staff was notified
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_schedules', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->dropColumn(['staff_id', 'contact_number', 'venue', 'proof_image', 'proof_uploaded_at', 'notified_at']);
        });
    }
};
