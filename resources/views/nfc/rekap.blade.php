<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi NFC</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 min-h-screen p-8">

    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="bg-blue-500 bg-opacity-20 border border-blue-400 border-opacity-30 rounded-xl p-2">
                        <span class="text-2xl">📋</span>
                    </div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Rekap Absensi</h1>
                </div>
                <p class="text-slate-400 text-sm ml-14">Data absensi mahasiswa via NFC</p>
            </div>
            <a href="/nfc"
               class="flex items-center gap-2 bg-white bg-opacity-10 hover:bg-opacity-20 border border-white border-opacity-10 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200">
                ← Kembali
            </a>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white bg-opacity-5 border border-white border-opacity-10 rounded-2xl p-5">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Total Record</p>
                <p class="text-3xl font-bold text-white">{{ $absensis->count() }}</p>
            </div>
            <div class="bg-emerald-500 bg-opacity-10 border border-emerald-500 border-opacity-20 rounded-2xl p-5">
                <p class="text-emerald-400 text-xs font-semibold uppercase tracking-wider mb-1">Hadir</p>
                <p class="text-3xl font-bold text-white">{{ $absensis->where('status', 'hadir')->count() }}</p>
            </div>
            <div class="bg-yellow-500 bg-opacity-10 border border-yellow-500 border-opacity-20 rounded-2xl p-5">
                <p class="text-yellow-400 text-xs font-semibold uppercase tracking-wider mb-1">Terlambat</p>
                <p class="text-3xl font-bold text-white">{{ $absensis->where('status', 'terlambat')->count() }}</p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white bg-opacity-5 backdrop-blur border border-white border-opacity-10 rounded-2xl p-5 mb-6 shadow-xl">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-5 bg-blue-400 rounded-full"></div>
                <h2 class="font-bold text-white">Filter Data</h2>
            </div>
            <form method="GET" action="/nfc/rekap" class="flex gap-4 items-end flex-wrap">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                           class="bg-white bg-opacity-10 border border-white border-opacity-10 rounded-xl p-3 text-sm text-white outline-none focus:ring-2 focus:ring-blue-400 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Mata Kuliah</label>
                    <select name="mata_kuliah"
                            class="bg-white bg-opacity-10 border border-white border-opacity-10 rounded-xl p-3 text-sm text-white outline-none focus:ring-2 focus:ring-blue-400 transition">
                        <option value="" class="bg-slate-800">Semua Mata Kuliah</option>
                        <option value="Kecerdasan Buatan" class="bg-slate-800" {{ request('mata_kuliah') == 'Kecerdasan Buatan' ? 'selected' : '' }}>Kecerdasan Buatan</option>
                        <option value="Kecerdasan Buatan (Praktikum)" class="bg-slate-800" {{ request('mata_kuliah') == 'Kecerdasan Buatan (Praktikum)' ? 'selected' : '' }}>Kecerdasan Buatan (Praktikum)</option>
                        <option value="Aplikasi Mobile" class="bg-slate-800" {{ request('mata_kuliah') == 'Aplikasi Mobile' ? 'selected' : '' }}>Aplikasi Mobile</option>
                        <option value="Aplikasi Mobile (Praktikum)" class="bg-slate-800" {{ request('mata_kuliah') == 'Aplikasi Mobile (Praktikum)' ? 'selected' : '' }}>Aplikasi Mobile (Praktikum)</option>
                        <option value="Pengujian Perangkat Lunak" class="bg-slate-800" {{ request('mata_kuliah') == 'Pengujian Perangkat Lunak' ? 'selected' : '' }}>Pengujian Perangkat Lunak</option>
                        <option value="Pengujian Perangkat Lunak (Praktikum)" class="bg-slate-800" {{ request('mata_kuliah') == 'Pengujian Perangkat Lunak (Praktikum)' ? 'selected' : '' }}>Pengujian Perangkat Lunak (Praktikum)</option>
                        <option value="Manajemen Proyek Perangkat Lunak" class="bg-slate-800" {{ request('mata_kuliah') == 'Manajemen Proyek Perangkat Lunak' ? 'selected' : '' }}>Manajemen Proyek Perangkat Lunak</option>
                        <option value="Manajemen Proyek Perangkat Lunak (Praktikum)" class="bg-slate-800" {{ request('mata_kuliah') == 'Manajemen Proyek Perangkat Lunak (Praktikum)' ? 'selected' : '' }}>Manajemen Proyek Perangkat Lunak (Praktikum)</option>
                        <option value="Workshop Desain UI" class="bg-slate-800" {{ request('mata_kuliah') == 'Workshop Desain UI' ? 'selected' : '' }}>Workshop Desain UI</option>
                        <option value="Workshop Pengembangan Perangkat Lunak WEB (Framework) Praktikum" class="bg-slate-800" {{ request('mata_kuliah') == 'Workshop Pengembangan Perangkat Lunak WEB (Framework) Praktikum' ? 'selected' : '' }}>Workshop Pengembangan Perangkat Lunak WEB (Framework) Praktikum</option>
                    </select>
                </div>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-lg shadow-blue-500/20 transition-all duration-200 hover:scale-105">
                    🔍 Filter
                </button>
                <a href="/nfc/rekap"
                   class="bg-white bg-opacity-10 hover:bg-opacity-20 border border-white border-opacity-10 text-slate-300 px-6 py-3 rounded-xl text-sm font-semibold transition-all duration-200">
                    Reset
                </a>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="bg-white bg-opacity-5 backdrop-blur border border-white border-opacity-10 rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white border-opacity-10 flex items-center gap-2">
                <div class="w-1 h-5 bg-emerald-400 rounded-full"></div>
                <h2 class="font-bold text-white text-lg">Data Absensi</h2>
                <span class="ml-auto bg-white bg-opacity-10 text-slate-300 text-xs px-3 py-1 rounded-full font-medium">
                    {{ $absensis->count() }} record
                </span>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white border-opacity-10">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">#</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">NIM</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Jam</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $i => $a)
                    <tr class="border-b border-white border-opacity-5 hover:bg-white hover:bg-opacity-5 transition">
                        <td class="px-6 py-4 text-slate-500 text-xs">{{ $i + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-white">{{ $a->kartu->nama_pemilik ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-300">{{ $a->kartu->nim ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-300">{{ $a->mata_kuliah }}</td>
                        <td class="px-6 py-4 text-slate-300">{{ $a->tanggal }}</td>
                        <td class="px-6 py-4 font-mono text-slate-300 text-xs">{{ $a->jam_masuk }}</td>
                        <td class="px-6 py-4">
                            @if($a->status === 'hadir')
                                <span class="bg-emerald-500 bg-opacity-20 text-emerald-400 border border-emerald-500 border-opacity-30 px-3 py-1 rounded-full text-xs font-semibold">● Hadir</span>
                            @elseif($a->status === 'terlambat')
                                <span class="bg-yellow-500 bg-opacity-20 text-yellow-400 border border-yellow-500 border-opacity-30 px-3 py-1 rounded-full text-xs font-semibold">● Terlambat</span>
                            @else
                                <span class="bg-red-500 bg-opacity-20 text-red-400 border border-red-500 border-opacity-30 px-3 py-1 rounded-full text-xs font-semibold">● Tidak Hadir</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="text-4xl mb-3">📭</div>
                            <p class="font-medium text-slate-400">Belum ada data absensi</p>
                            <p class="text-xs text-slate-500 mt-1">Data akan muncul setelah mahasiswa melakukan scan kartu NFC</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>