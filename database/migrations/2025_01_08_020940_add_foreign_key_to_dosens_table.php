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
        Schema::table('dosens', function (Blueprint $table) {
            $table->foreign('pangkat_golongan_id')->references('id')->on('pangkat_golongans')->onDelete('cascade');
            $table->foreign('jabatan_id')->references('id')->on('jabatans')->onDelete('cascade');
            $table->foreign('konsentrasi_id')->references('id')->on('konsentrasis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['pangkat_golongan_id']);
            $table->dropForeign(['jabatan_id']);
            $table->dropForeign(['konsentrasi_id']);
        });
    }
};
