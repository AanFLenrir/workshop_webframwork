<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Kartu NFC</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 min-h-screen p-8">

    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="bg-blue-500 bg-opacity-20 border border-blue-400 border-opacity-30 rounded-xl p-2">
                        <span class="text-2xl">🪪</span>
                    </div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Manajemen Kartu NFC</h1>
                </div>
                <p class="text-slate-400 text-sm ml-14">Daftarkan & kelola kartu NFC mahasiswa</p>
            </div>
            <div class="flex gap-3">
                <a href="/nfc/scanner" target="_blank"
                   class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-emerald-500/20 transition-all duration-200 hover:scale-105">
                    📡 Buka Scanner
                </a>
                <a href="/nfc/rekap"
                   class="flex items-center gap-2 bg-blue-500 hover:bg-blue-400 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-blue-500/20 transition-all duration-200 hover:scale-105">
                    📋 Rekap Absensi
                </a>
            </div>
        </div>

        {{-- Alert --}}
        <div id="alert" class="hidden mb-6 p-4 rounded-xl text-sm font-medium border"></div>

        {{-- Form Daftar Kartu --}}
        <div class="bg-white bg-opacity-5 backdrop-blur border border-white border-opacity-10 rounded-2xl p-6 mb-6 shadow-xl">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-5 bg-blue-400 rounded-full"></div>
                <h2 class="font-bold text-white text-lg">Daftarkan Kartu Baru</h2>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Serial Number NFC</label>
                    <input type="text" id="serial_number" placeholder="Scan kartu atau input manual"
                           class="w-full bg-white bg-opacity-10 border border-white border-opacity-10 rounded-xl p-3 text-sm text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Pemilik</label>
                    <input type="text" id="nama_pemilik" placeholder="Nama lengkap"
                           class="w-full bg-white bg-opacity-10 border border-white border-opacity-10 rounded-xl p-3 text-sm text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">NIM</label>
                    <input type="text" id="nim" placeholder="Nomor Induk Mahasiswa"
                           class="w-full bg-white bg-opacity-10 border border-white border-opacity-10 rounded-xl p-3 text-sm text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kelas</label>
                    <input type="text" id="kelas" placeholder="Contoh: TI-3A"
                           class="w-full bg-white bg-opacity-10 border border-white border-opacity-10 rounded-xl p-3 text-sm text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition">
                </div>
            </div>
            <div class="mt-5 flex gap-3">
                <button onclick="daftarkanKartu()"
                        class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-blue-500/20 transition-all duration-200 hover:scale-105">
                    ✅ Daftarkan Kartu
                </button>
                <button onclick="scanUntukDaftar()"
                        class="bg-white bg-opacity-10 hover:bg-opacity-20 border border-white border-opacity-10 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200">
                    📡 Scan Serial
                </button>
            </div>
        </div>

        {{-- Tabel Kartu --}}
        <div class="bg-white bg-opacity-5 backdrop-blur border border-white border-opacity-10 rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white border-opacity-10 flex items-center gap-2">
                <div class="w-1 h-5 bg-emerald-400 rounded-full"></div>
                <h2 class="font-bold text-white text-lg">Daftar Kartu Terdaftar</h2>
                <span class="ml-auto bg-white bg-opacity-10 text-slate-300 text-xs px-3 py-1 rounded-full font-medium">
                    {{ $kartus->count() }} kartu
                </span>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white border-opacity-10">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Serial Number</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">NIM</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Kelas</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-kartu">
                    @forelse($kartus as $kartu)
                    <tr class="border-b border-white border-opacity-5 hover:bg-white hover:bg-opacity-5 transition" id="row-{{ $kartu->id }}">
                        <td class="px-6 py-4 font-mono text-xs text-slate-300 bg-white bg-opacity-5">{{ $kartu->serial_number }}</td>
                        <td class="px-6 py-4 font-semibold text-white">{{ $kartu->nama_pemilik }}</td>
                        <td class="px-6 py-4 text-slate-300">{{ $kartu->nim }}</td>
                        <td class="px-6 py-4 text-slate-300">{{ $kartu->kelas ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($kartu->aktif)
                                <span class="bg-emerald-500 bg-opacity-20 text-emerald-400 border border-emerald-500 border-opacity-30 px-3 py-1 rounded-full text-xs font-semibold">● Aktif</span>
                            @else
                                <span class="bg-red-500 bg-opacity-20 text-red-400 border border-red-500 border-opacity-30 px-3 py-1 rounded-full text-xs font-semibold">● Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="hapusKartu({{ $kartu->id }})"
                                    class="bg-red-500 bg-opacity-20 hover:bg-opacity-40 text-red-400 border border-red-500 border-opacity-30 px-3 py-1 rounded-lg text-xs font-semibold transition">
                                🗑 Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <div class="text-4xl mb-3">🪪</div>
                            <p class="font-medium">Belum ada kartu terdaftar</p>
                            <p class="text-xs mt-1">Daftarkan kartu NFC mahasiswa menggunakan form di atas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<script>
    const CSRF = document.querySelector('meta[name=csrf-token]').content;

    function showAlert(msg, type = 'success') {
        const el = document.getElementById('alert');
        if (type === 'success') {
            el.className = 'mb-6 p-4 rounded-xl text-sm font-medium border bg-emerald-500 bg-opacity-20 text-emerald-400 border-emerald-500 border-opacity-30';
        } else {
            el.className = 'mb-6 p-4 rounded-xl text-sm font-medium border bg-red-500 bg-opacity-20 text-red-400 border-red-500 border-opacity-30';
        }
        el.textContent = msg;
        el.classList.remove('hidden');
        setTimeout(() => el.classList.add('hidden'), 4000);
    }

    async function daftarkanKartu() {
        const body = {
            serial_number: document.getElementById('serial_number').value,
            nama_pemilik:  document.getElementById('nama_pemilik').value,
            nim:           document.getElementById('nim').value,
            kelas:         document.getElementById('kelas').value,
        };

        const res  = await fetch('/nfc/kartu', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(body),
        });
        const data = await res.json();

        if (res.ok) {
            showAlert(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            const errors = data.errors ? Object.values(data.errors).flat().join(', ') : data.message;
            showAlert(errors, 'error');
        }
    }

    async function hapusKartu(id) {
        if (!confirm('Yakin hapus kartu ini?')) return;
        const res = await fetch(`/nfc/kartu/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF },
        });
        if (res.ok) {
            document.getElementById(`row-${id}`).remove();
        }
    }

    async function scanUntukDaftar() {
        if (!('NDEFReader' in window)) {
            alert('Web NFC hanya tersedia di Android Chrome.');
            return;
        }
        try {
            const ndef = new NDEFReader();
            await ndef.scan();
            alert('Dekatkan kartu NFC ke HP...');
            ndef.addEventListener('reading', ({ serialNumber }) => {
                document.getElementById('serial_number').value = serialNumber;
            }, { once: true });
        } catch (err) {
            alert('Error: ' + err.message);
        }
    }
</script>
</body>
</html>