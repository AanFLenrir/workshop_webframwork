<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->bigIncrements('idbuku'); // Primary Key kustom
            $table->string('kode', 20)->unique();
            $table->string('judul', 150);
            $table->string('pengarang', 100);
            $table->unsignedBigInteger('idkategori');
            // Jika Anda sudah punya tabel kategori, tambahkan foreign key:
            // $table->foreign('idkategori')->references('idkategori')->on('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};