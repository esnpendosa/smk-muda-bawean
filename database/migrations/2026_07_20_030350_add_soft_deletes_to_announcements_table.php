<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The original migration already had softDeletes() but the column was missing
        // from the production database. This migration adds it safely.
        if (!Schema::hasColumn('announcements', 'deleted_at')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->softDeletes()->after('published_at');
            });
        }
    }

    public function down(): void
    {
        // Only drop if we added it (i.e., it wasn't already there from the original migration)
        // We leave it in place on rollback since original migration also defines it.
    }
};
