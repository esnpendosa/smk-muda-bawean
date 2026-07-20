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
        Schema::create('ppdb_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('full_name', 100);
            $table->string('birth_place', 50);
            $table->date('birth_date');
            $table->string('previous_school', 100);
            $table->string('parent_name', 100);
            $table->string('phone', 15);
            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu')->index();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_registrations');
    }
};
