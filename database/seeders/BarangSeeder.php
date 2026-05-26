<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            ['nama' => 'Beras Premium 5kg',   'harga' => 65000],
            ['nama' => 'Minyak Goreng 1L',    'harga' => 18500],
            ['nama' => 'Gula Pasir 1kg',      'harga' => 14000],
            ['nama' => 'Tepung Terigu 1kg',   'harga' => 11000],
            ['nama' => 'Kecap Manis 135ml',   'harga' =>  9500],
            ['nama' => 'Sambal Botol 140ml',  'harga' => 12000],
            ['nama' => 'Sabun Mandi Batang',  'harga' =>  4500],
            ['nama' => 'Shampoo Sachet',      'harga' =>  2000],
            ['nama' => 'Deterjen Bubuk 800g', 'harga' => 20000],
            ['nama' => 'Minuman Teh Botol',   'harga' =>  5000],
            ['nama' => 'Indomie Goreng',      'harga' =>  3500],
            ['nama' => 'Susu UHT 200ml',      'harga' =>  6500],
        ];

        foreach ($barangs as $b) {
            // id_barang dikosongkan → trigger MySQL yang mengisi
            DB::insert('INSERT INTO barang (id_barang, nama, harga) VALUES (?, ?, ?)', [
                '', $b['nama'], $b['harga']
            ]);
        }
    }
}