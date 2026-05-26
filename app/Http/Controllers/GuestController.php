<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GuestController extends Controller
{
    public function index()
    {
        $polis = Poli::where('aktif', true)->get();
        return view('guest.index', compact('polis'));
    }

    public function daftar(Request $request)
    {
        $request->validate([
            'poli_id' => 'required|exists:polis,id',
            'nama'    => 'required|string|max:100',
        ]);

        $poli    = Poli::findOrFail($request->poli_id);
        $antrian = Antrian::create([
            'poli_id' => $poli->id,
            'nomor'   => $poli->nextNomor(),
            'nama'    => $request->nama,
            'status'  => 'waiting',
        ]);

        // Sync cache setelah daftar
        $this->syncCache();

        return redirect('/tiket/' . $antrian->id);
    }

    public function tiket(Antrian $antrian)
    {
        return view('guest.tiket', compact('antrian'));
    }

    private function syncCache()
    {
        $antrian = Antrian::with('poli')->where('status', 'waiting')->orderBy('nomor')->get();
        $terlambat = Antrian::with('poli')->where('status', 'terlambat')->orderBy('nomor')->get();
        $dipanggil = Antrian::with('poli')->where('status', 'dipanggil')->latest('updated_at')->first();

        Cache::put('antrian_state', [
            'sedang_dipanggil' => $dipanggil,
            'antrian'          => $antrian,
            'terlambat'        => $terlambat,
        ], now()->addHours(8));
    }
}
