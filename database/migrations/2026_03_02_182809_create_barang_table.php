<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->string('id_barang', 8)->primary();
            $table->string('nama', 50);
            $table->integer('harga');
            $table->timestamp('tgl_input')->useCurrent();
        });

        // Trigger MySQL untuk generate ID otomatis
        DB::unprepared("
            CREATE TRIGGER trigger_id_barang BEFORE INSERT ON barang
            FOR EACH ROW
            BEGIN
                DECLARE v_count INT;
                DECLARE v_tgl VARCHAR(6);
                
                -- Ambil tanggal hari ini dalam format YYMMDD
                SET v_tgl = DATE_FORMAT(NOW(), '%y%m%d');
                
                -- Hitung jumlah record hari ini untuk nomor urut
                SELECT COUNT(*) + 1 INTO v_count 
                FROM barang 
                WHERE DATE(tgl_input) = CURRENT_DATE;
                
                -- Set id_barang dengan format YYMMDD + 2 digit urutan (contoh: 26052601)
                SET NEW.id_barang = CONCAT(v_tgl, LPAD(v_count, 2, '0'));
            END
        ");
    }

    public function down(): void
    {
        // Menghapus trigger sebelum menghapus tabel
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_id_barang');
        Schema::dropIfExists('barang');
    }
};