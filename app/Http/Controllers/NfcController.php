<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\KartuNfc;
use Illuminate\Http\Request;

class NfcController extends Controller
{
    // Halaman daftar kartu NFC
    public function index()
    {
        $kartus = KartuNfc::latest()->get();
        return view('nfc.index', compact('kartus'));
    }

    // Simpan kartu NFC baru
    public function store(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|unique:kartu_nfc,serial_number',
            'nama_pemilik'  => 'required|string|max:100',
            'nim'           => 'required|string|unique:kartu_nfc,nim',
            'kelas'         => 'nullable|string|max:20',
        ]);

        KartuNfc::create($request->all());

        return response()->json(['message' => 'Kartu berhasil didaftarkan']);
    }

    // Hapus kartu NFC
    public function destroy(KartuNfc $kartuNfc)
    {
        $kartuNfc->delete();
        return response()->json(['message' => 'Kartu berhasil dihapus']);
    }

    // Halaman scanner NFC (dibuka di HP)
    public function scanner()
    {
        return view('nfc.scanner');
    }

    // Proses scan NFC → catat absensi
    public function scan(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string',
            'mata_kuliah'   => 'required|string',
        ]);

        $kartu = KartuNfc::where('serial_number', $request->serial_number)
                         ->where('aktif', true)
                         ->first();

        if (!$kartu) {
            return response()->json([
                'status'  => 'tidak_terdaftar',
                'message' => 'Kartu NFC tidak terdaftar atau tidak aktif.',
            ], 404);
        }

        // Cek apakah sudah absen hari ini untuk matkul yang sama
        $sudahAbsen = Absensi::where('kartu_nfc_id', $kartu->id)
                             ->where('mata_kuliah', $request->mata_kuliah)
                             ->where('tanggal', today())
                             ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'status'  => 'duplikat',
                'message' => "Mahasiswa {$kartu->nama_pemilik} sudah tercatat hadir hari ini.",
                'nama'    => $kartu->nama_pemilik,
                'nim'     => $kartu->nim,
            ]);
        }

        // Tentukan status: terlambat jika lewat jam 08:00
        $jamMasuk = now()->format('H:i:s');
        $status   = now()->format('H:i') > '08:00' ? 'terlambat' : 'hadir';

        Absensi::create([
            'kartu_nfc_id' => $kartu->id,
            'mata_kuliah'  => $request->mata_kuliah,
            'tanggal'      => today(),
            'jam_masuk'    => $jamMasuk,
            'status'       => $status,
            'serial_number'=> $request->serial_number,
        ]);

        return response()->json([
            'status'  => $status,
            'message' => "Absensi berhasil dicatat!",
            'nama'    => $kartu->nama_pemilik,
            'nim'     => $kartu->nim,
            'kelas'   => $kartu->kelas,
            'jam'     => $jamMasuk,
        ]);
    }

    // Halaman rekap absensi
    public function rekap(Request $request)
    {
        $query = Absensi::with('kartu')
                        ->orderBy('tanggal', 'desc')
                        ->orderBy('jam_masuk', 'desc');

        if ($request->tanggal) {
            $query->where('tanggal', $request->tanggal);
        }
        if ($request->mata_kuliah) {
            $query->where('mata_kuliah', $request->mata_kuliah);
        }

        $absensis    = $query->get();
        $mataKuliahs = Absensi::distinct()->pluck('mata_kuliah');

        return view('nfc.rekap', compact('absensis', 'mataKuliahs'));
    }
}