<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kartu_nfc', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->string('nama_pemilik');
            $table->string('nim')->unique();
            $table->string('kelas')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kartu_nfc');
    }
};