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
        Schema::disableForeignKeyConstraints();

        Schema::create('plottings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained();
            $table->foreignId('matakuliah_id')->constrained();
            $table->string('kelas');
            $table->string('semester');
            $table->integer('tahun');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plottings');
    }
};
