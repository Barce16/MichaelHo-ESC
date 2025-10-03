<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inclusions', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('is_active');
        });

        DB::table('package_inclusion')
            ->select('inclusion_id', 'notes')
            ->whereNotNull('notes')
            ->orderByRaw('LENGTH(notes) DESC')
            ->chunkById(500, function ($rows) {

                static $seen = [];
                foreach ($rows as $row) {
                    $incId = $row->inclusion_id;
                    if (isset($seen[$incId])) continue;
                    $note = trim((string)$row->notes);
                    if ($note !== '') {
                        DB::table('inclusions')->where('id', $incId)->update(['notes' => $note]);
                        $seen[$incId] = true;
                    }
                }
            }, 'inclusion_id');
    }

    public function down(): void
    {
        Schema::table('inclusions', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
};
