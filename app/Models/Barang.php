<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    // Primary key bukan integer dan bukan auto-increment
    protected $primaryKey = 'id_barang';
    public    $incrementing = false;
    protected $keyType      = 'string';

    // Tidak pakai created_at / updated_at bawaan Laravel
    public $timestamps = false;

    protected $table = 'barang';

    protected $fillable = ['nama', 'harga'];

    // id_barang diisi oleh trigger MySQL, jadi kita override save()
    // agar id_barang bisa dikosongkan saat insert
    public static function insertViaRaw(array $data): void
    {
        \Illuminate\Support\Facades\DB::insert(
            'INSERT INTO barang (id_barang, nama, harga) VALUES (?, ?, ?)',
            ['', $data['nama'], $data['harga']]
        );
    }
}