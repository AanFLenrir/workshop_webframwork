<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    private function syncCache()
    {
        $antrian = Antrian::with('poli')
            ->where('status', 'waiting')
            ->orderBy('nomor')
            ->get();

        $terlambat = Antrian::with('poli')
            ->where('status', 'terlambat')
            ->orderBy('nomor')
            ->get();

        $dipanggil = Antrian::with('poli')
            ->where('status', 'dipanggil')
            ->latest('updated_at')
            ->first();

        $state = [
            'sedang_dipanggil' => $dipanggil,
            'antrian'          => $antrian,
            'terlambat'        => $terlambat,
        ];

        Cache::put('antrian_state', $state, now()->addHours(8));
        return $state;
    }

    public function index()
    {
        $polis = Poli::where('aktif', true)->get();
        return view('admin.index', compact('polis'));
    }

    public function dashboard($kode)
    {
        $poli = Poli::where('id', $kode)->firstOrFail();
        $state = $this->syncCache();
        return view('admin.dashboard', compact('poli', 'state'));
    }

    public function panggil(Request $request)
    {
        $antrian = Antrian::where('status', 'waiting')
            ->orderBy('nomor')
            ->first();

        if (!$antrian) {
            return response()->json(['message' => 'Tidak ada antrian'], 404);
        }

        // Set semua yang dipanggil sebelumnya jadi selesai
        Antrian::where('status', 'dipanggil')->update(['status' => 'selesai']);

        $antrian->update(['status' => 'dipanggil']);
        $this->syncCache();

        return response()->json(['message' => 'Berhasil', 'antrian' => $antrian->load('poli')]);
    }

    public function lewati(Antrian $antrian)
    {
        $antrian->update(['status' => 'terlambat']);
        $this->syncCache();

        return response()->json(['message' => 'Ditandai terlambat']);
    }

    public function panggilLagi(Antrian $antrian)
    {
        Antrian::where('status', 'dipanggil')->update(['status' => 'selesai']);
        $antrian->update(['status' => 'dipanggil']);
        $this->syncCache();

        return response()->json(['message' => 'Dipanggil lagi', 'antrian' => $antrian->load('poli')]);
    }
}
