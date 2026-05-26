<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class PdfController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ════════════════════════════════
    // SERTIFIKAT
    // ════════════════════════════════
    public function sertifikat()
    {
        $loggedUser = auth()->user();

        // Jika ADMIN → ambil semua user
        if ($loggedUser->role === 'admin') {
            $users = User::where('role', 'user')->get();
        } else {
            // Jika USER → hanya dirinya sendiri
            $users = collect([$loggedUser]);
        }

        $data = [
            'users' => $users,
            'program' => 'Pelatihan ORM & Mendeley untuk Pengelolaan Referensi Perpustakaan',
            'penyelenggara' => 'yang diselenggarakan oleh Unit Perpustakaan Pusat Universitas Airlangga',
            'kota' => 'Surabaya',
            'tanggal' => now()->translatedFormat('d F Y'),
            'ttd_kiri_nama' => 'Dr. Hendra Wijaya, M.Lib',
            'ttd_kiri_nip' => '196908231995031002',
            'ttd_kanan_nama' => 'Siti Aminah, S.Kom, M.T',
            'ttd_kanan_nip' => '198204122009122001',
        ];

        $pdf = Pdf::loadView('pdf.sertifikat', $data)
            ->setPaper('A4', 'landscape');

        return $pdf->stream('sertifikat.pdf');
    }

    // ════════════════════════════════
    // UNDANGAN
    // ════════════════════════════════
    public function undangan()
    {
        $loggedUser = auth()->user();

        // Jika ADMIN → semua user
        if ($loggedUser->role === 'admin') {
            $users = User::where('role', 'user')->get();
        } else {
            $users = collect([$loggedUser]);
        }

        $data = [
            'users' => $users,
            'nomor_surat' => '024/PERPUS-UNAIR/SK/II/2026',
            'lampiran' => '1 (Satu) Berkas Jadwal Kegiatan',
            'perihal' => 'Undangan Pelatihan ORM & Mendeley',
            'judul_acara' => 'Pelatihan ORM & Mendeley untuk Pengelolaan Referensi Perpustakaan',
            'kepada_instansi' => 'Universitas Airlangga',
            'hari_tanggal' => 'Sabtu, 28 Februari 2026',
            'waktu' => '09.00 – 16.00 WIB',
            'tempat' => 'Ruang Seminar Perpustakaan Lt. 2 – Universitas Airlangga',
            'materi' => 'ORM dengan Eloquent Laravel & Manajemen Referensi menggunakan Mendeley',
            'narasumber' => 'Dr. Budi Santoso, M.Kom',
            'kota' => 'Surabaya',
            'tanggal' => now()->translatedFormat('d F Y'),
            'ttd_role' => 'Kepala Unit Perpustakaan Pusat',
            'ttd_nama' => 'Dr. Hendra Wijaya, M.Lib',
            'ttd_nip' => '196908231995031002',
        ];

        $pdf = Pdf::loadView('pdf.undangan', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->stream('undangan.pdf');
    }
}