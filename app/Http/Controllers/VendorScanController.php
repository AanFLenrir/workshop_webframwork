<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class VendorScanController extends Controller
{
    public function index()
    {
        return view('vendor.scan');
    }

    public function cekPesanan($id_pesanan)
    {
        // Cari berdasarkan order_code, bukan id
        $pesanan = Order::with('items.barang')
                        ->where('order_code', $id_pesanan)
                        ->first();

        if (!$pesanan) {
            return response()->json(['status' => 'not_found'], 404);
        }

        $menu = $pesanan->items->map(function ($item) {
            return [
                'nama_menu' => $item->barang->nama,
                'qty'       => $item->quantity,
                'harga'     => $item->subtotal,
            ];
        });

        return response()->json([
            'status'       => 'found',
            'id_pesanan'   => $pesanan->id,
            'order_code'   => $pesanan->order_code,
            'status_bayar' => $pesanan->payment_status ?? 'lunas',
            'menu'         => $menu,
        ]);
    }
}