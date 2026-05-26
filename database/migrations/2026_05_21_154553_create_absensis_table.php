<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kartu_nfc_id')->constrained('kartu_nfc')->cascadeOnDelete();
            $table->string('mata_kuliah');
            $table->date('tanggal');
            $table->time('jam_masuk');
            $table->enum('status', ['hadir', 'terlambat', 'tidak_hadir'])->default('hadir');
            $table->string('serial_number');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};