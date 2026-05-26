<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class TokoController extends Controller
{
    // ── List toko + form input titik awal ──
    public function index()
    {
        $tokos = Toko::latest()->get();
        return view('toko.index', compact('tokos'));
    }

    // ── Simpan toko baru ──
    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'alamat'    => 'nullable|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy'  => 'required|numeric',
        ]);

        $barcode = 'TKO-' . strtoupper(uniqid());

        Toko::create([
            'barcode'   => $barcode,
            'nama_toko' => $request->nama_toko,
            'alamat'    => $request->alamat,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy'  => $request->accuracy,
        ]);

        return redirect()->route('toko.index')
                         ->with('success', 'Toko berhasil ditambahkan!');
    }

    // ── Cetak barcode toko ──
    public function cetakBarcode($id)
    {
        $toko      = Toko::findOrFail($id);
        $generator = new BarcodeGeneratorPNG();
        $barcode   = base64_encode(
            $generator->getBarcode($toko->barcode, $generator::TYPE_CODE_128)
        );
        return view('toko.barcode', compact('toko', 'barcode'));
    }

    // ── Halaman kunjungan toko (scan + geolocation) ──
    public function kunjungan()
    {
        $riwayat = Kunjungan::with('toko', 'user')
                            ->where('user_id', auth()->id())
                            ->latest()
                            ->take(10)
                            ->get();
        return view('toko.kunjungan', compact('riwayat'));
    }

    // ── API: cari toko by barcode ──
    public function cariToko($barcode)
    {
        $toko = Toko::where('barcode', $barcode)->first();

        if (!$toko) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json([
            'status'    => 'found',
            'id'        => $toko->id,
            'nama_toko' => $toko->nama_toko,
            'alamat'    => $toko->alamat ?? '-',
            'latitude'  => $toko->latitude,
            'longitude' => $toko->longitude,
            'accuracy'  => $toko->accuracy,
        ]);
    }

    // ── API: simpan laporan kunjungan ──
    public function simpanKunjungan(Request $request)
    {
        $request->validate([
            'toko_id'         => 'required|exists:tokos,id',
            'latitude_sales'  => 'required|numeric',
            'longitude_sales' => 'required|numeric',
            'accuracy_sales'  => 'required|numeric',
        ]);

        $toko = Toko::findOrFail($request->toko_id);

        // Hitung jarak pakai formula Haversine
        $jarak = $this->haversine(
            $toko->latitude,
            $toko->longitude,
            $request->latitude_sales,
            $request->longitude_sales
        );

        // threshold efektif = 300m + accuracy toko + accuracy sales
        $threshold = 300 + $toko->accuracy + $request->accuracy_sales;
        $status    = $jarak <= $threshold ? 'diterima' : 'ditolak';

        Kunjungan::create([
            'toko_id'         => $toko->id,
            'user_id'         => auth()->id(),
            'latitude_sales'  => $request->latitude_sales,
            'longitude_sales' => $request->longitude_sales,
            'accuracy_sales'  => $request->accuracy_sales,
            'jarak_meter'     => round($jarak, 2),
            'status'          => $status,
        ]);

        return response()->json([
            'status'    => $status,
            'jarak'     => round($jarak, 1),
            'threshold' => round($threshold, 1),
        ]);
    }

    // ── Formula Haversine ──
    private function haversine($lat1, $lng1, $lat2, $lng2)
    {
        $R    = 6371000; // radius bumi dalam meter
        $dLat = ($lat2 - $lat1) * M_PI / 180;
        $dLng = ($lng2 - $lng1) * M_PI / 180;
        $a    = sin($dLat / 2) ** 2
              + cos($lat1 * M_PI / 180) * cos($lat2 * M_PI / 180) * sin($dLng / 2) ** 2;
        $c    = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }
}