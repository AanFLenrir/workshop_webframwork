<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarcodeReaderController extends Controller
{
    public function index()
    {
        return view('barcode.reader');
    }

    public function cariBarang($kode)
    {
        $barang = Barang::where('id_barang', $kode)->first();

        if (!$barang) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json([
            'status'       => 'found',
            'id_barang'    => $barang->id_barang,
            'nama_barang' => $barang->nama,
            'harga'        => $barang->harga,
        ]);
    }
}