<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ─────────────────────────────────────────────────────
    // Tampil daftar barang
    // ─────────────────────────────────────────────────────
    public function index()
    {
        $barangs = DB::table('barang')->orderByDesc('tgl_input')->get();
        return view('barang.index', compact('barangs'));
    }

    // ─────────────────────────────────────────────────────
    // Simpan barang baru
    // ─────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:50',
            'harga' => 'required|integer|min:1',
        ]);

        DB::insert('INSERT INTO barang (id_barang, nama, harga) VALUES (?, ?, ?)', [
            '', $request->nama, $request->harga
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    // ─────────────────────────────────────────────────────
    // Update barang
    // ─────────────────────────────────────────────────────
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama'  => 'required|string|max:50',
            'harga' => 'required|integer|min:1',
        ]);

        DB::table('barang')
            ->where('id_barang', $id)
            ->update([
                'nama'  => $request->nama,
                'harga' => $request->harga,
            ]);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui!');
    }

    // ─────────────────────────────────────────────────────
    // Hapus barang
    // ─────────────────────────────────────────────────────
    public function destroy(string $id)
    {
        DB::table('barang')->where('id_barang', $id)->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }

    // ─────────────────────────────────────────────────────
    // Cetak PDF tag harga (tanpa barcode — versi lama)
    // ─────────────────────────────────────────────────────
    public function cetakPdf(Request $request)
    {
        $request->validate([
            'ids'    => 'required|string',
            'coordX' => 'required|integer|min:1|max:5',
            'coordY' => 'required|integer|min:1|max:8',
        ]);

        $ids = collect(explode(',', $request->ids))
            ->map(fn($v) => trim($v))
            ->filter(fn($v) => preg_match('/^[a-zA-Z0-9]+$/', $v))
            ->values();

        $barangs = DB::table('barang')
            ->whereIn('id_barang', $ids)
            ->get()
            ->keyBy('id_barang');

        $ordered   = $ids->map(fn($id) => $barangs->get($id))->filter()->values();
        $coordX    = (int) $request->coordX;
        $coordY    = (int) $request->coordY;
        $startSlot = ($coordY - 1) * 5 + ($coordX - 1);

        $pdf = Pdf::loadView('barang.pdf', [
            'barangs'   => $ordered,
            'startSlot' => $startSlot,
            'totalSlot' => 40,
            'cols'      => 5,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('tag_harga_' . now()->format('YmdHis') . '.pdf');
    }

    // ─────────────────────────────────────────────────────
    // Cetak PDF tag harga DENGAN BARCODE (Studi Kasus 1)
    //
    // Cara kerja:
    //  1. Ambil data barang berdasarkan ids yang dipilih
    //  2. Generate barcode PNG dari id_barang masing-masing
    //  3. Encode ke base64 agar bisa ditempel di HTML/PDF
    //  4. Kirim ke view pdf-barcode.blade.php
    //  5. Render sebagai PDF via DomPDF
    // ─────────────────────────────────────────────────────
    public function cetakPdfBarcode(Request $request)
    {
        $request->validate([
            'ids'    => 'required|string',
            'coordX' => 'required|integer|min:1|max:5',
            'coordY' => 'required|integer|min:1|max:8',
        ]);

        // Sanitasi ids — hanya huruf & angka
        $ids = collect(explode(',', $request->ids))
            ->map(fn($v) => trim($v))
            ->filter(fn($v) => preg_match('/^[a-zA-Z0-9]+$/', $v))
            ->values();

        // Ambil data barang dari DB
        $barangs = DB::table('barang')
            ->whereIn('id_barang', $ids)
            ->get()
            ->keyBy('id_barang');

        // Pertahankan urutan sesuai urutan ids dari request
        $ordered = $ids->map(fn($id) => $barangs->get($id))->filter()->values();

        // Generate barcode untuk tiap barang
        $generator = new BarcodeGeneratorPNG();
        $barcodes  = [];

        foreach ($ordered as $barang) {
            // TYPE_CODE_128 = format barcode paling umum, bisa encode huruf+angka
            $barcodes[$barang->id_barang] = base64_encode(
                $generator->getBarcode(
                    (string) $barang->id_barang,
                    $generator::TYPE_CODE_128
                )
            );
        }

        // Hitung slot mulai di kertas label (grid 5 kolom x 8 baris = 40 label/halaman)
        $coordX    = (int) $request->coordX;
        $coordY    = (int) $request->coordY;
        $startSlot = ($coordY - 1) * 5 + ($coordX - 1);

        $pdf = Pdf::loadView('barang.pdf-barcode', [
            'barangs'   => $ordered,
            'barcodes'  => $barcodes,   // array [id_barang => base64_string]
            'startSlot' => $startSlot,
            'totalSlot' => 40,
            'cols'      => 5,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('tag_harga_barcode_' . now()->format('YmdHis') . '.pdf');
    }

    // ─────────────────────────────────────────────────────
    // Preview barcode satu barang (opsional — untuk cek hasil)
    // Akses: GET /barang/{id}/barcode
    // ─────────────────────────────────────────────────────
    public function previewBarcode(string $id)
    {
        $barang = DB::table('barang')->where('id_barang', $id)->first();

        abort_if(! $barang, 404, 'Barang tidak ditemukan.');

        $generator    = new BarcodeGeneratorPNG();
        $barcodeImage = base64_encode(
            $generator->getBarcode(
                (string) $barang->id_barang,
                $generator::TYPE_CODE_128
            )
        );

        return view('barang.tag-harga', compact('barang', 'barcodeImage'));
    }
}