<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Antrian</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            color: white;
            display: flex;
            flex-direction: column;
        }
        header {
            background: rgba(255,255,255,0.05);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        header h1 { font-size: 1.8rem; font-weight: 700; }
        header .clock { font-size: 1.5rem; font-weight: 600; color: #e94560; }
        .main { flex: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 30px; padding: 40px; }
        .panel {
            background: rgba(255,255,255,0.05);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
        }
        .panel-title {
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.5);
            margin-bottom: 20px;
        }
        .now-calling { text-align: center; padding: 20px; }
        .now-calling .nomor {
            font-size: 160px;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, #e94560, #f5a623);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: pulse 2s infinite;
        }
        .now-calling .nama { font-size: 2.5rem; font-weight: 700; margin-top: 10px; }
        .now-calling .poli {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.6);
            margin-top: 8px;
            background: rgba(233,69,96,0.2);
            display: inline-block;
            padding: 5px 20px;
            border-radius: 20px;
            border: 1px solid rgba(233,69,96,0.4);
        }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
        .antrian-list { list-style: none; }
        .antrian-list li {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 10px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .antrian-list li .nomor-badge {
            width: 50px; height: 50px;
            background: linear-gradient(135deg, #4B49AC, #7978E9);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: 700;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .antrian-list li .info .nama { font-weight: 600; font-size: 1rem; }
        .antrian-list li .info .poli { font-size: 0.8rem; color: rgba(255,255,255,0.5); }
        .empty-state { text-align: center; color: rgba(255,255,255,0.3); padding: 40px; font-size: 1rem; }
        .status-dot {
            display: inline-block;
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #4caf50;
            box-shadow: 0 0 8px #4caf50;
            margin-right: 8px;
        }
        .click-hint {
            position: fixed;
            bottom: 30px; right: 30px;
            background: rgba(255,255,255,0.1);
            color: white; border: none;
            padding: 12px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
    </style>
</head>
<body>
    <header>
        <div>
            <h1>🏥 Papan Antrian Digital</h1>
            <div style="font-size:0.85rem; color:rgba(255,255,255,0.5)">
                <span class="status-dot"></span>
                <span id="statusText">Terhubung</span>
            </div>
        </div>
        <div class="clock" id="clock">00:00:00</div>
    </header>

    <div class="main">
        <div class="panel">
            <div class="panel-title">🔔 Sedang Dipanggil</div>
            <div class="now-calling">
                <div class="nomor" id="nomorPanggil">—</div>
                <div class="nama" id="namaPanggil">Menunggu panggilan...</div>
                <div class="poli" id="poliPanggil">—</div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-title">⏳ Antrian Menunggu
                <span id="jumlahWaiting" style="background:rgba(255,255,255,0.1); padding:2px 10px; border-radius:20px; font-size:0.85rem; margin-left:8px;">0</span>
            </div>
            <ul class="antrian-list" id="listWaiting">
                <li class="empty-state">Tidak ada antrian</li>
            </ul>
        </div>
    </div>

    <div class="click-hint" id="clickHint">🔊 Klik halaman ini untuk mengaktifkan suara</div>

    <script>
        let suaraAktif = false;
        let nomorTerakhir = null;
        let sedangBerbicara = false;

        document.addEventListener('click', function() {
            suaraAktif = true;
            document.getElementById('clickHint').style.display = 'none';
        }, { once: true });

        function updateClock() {
            document.getElementById('clock').textContent = new Date().toLocaleTimeString('id-ID');
        }
        setInterval(updateClock, 1000);
        updateClock();

        function bunyiNotifikasi() {
            return new Promise((resolve) => {
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();

                    function nada(freq, start, dur, vol = 0.5) {
                        const osc  = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        osc.type = 'sine';
                        osc.frequency.value = freq;
                        gain.gain.setValueAtTime(vol, ctx.currentTime + start);
                        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + start + dur);
                        osc.start(ctx.currentTime + start);
                        osc.stop(ctx.currentTime + start + dur);
                    }

                    // Melodi Do-Mi-Sol lembut
                    nada(523, 0.0, 0.3);
                    nada(659, 0.35, 0.3);
                    nada(784, 0.7, 0.5);

                    setTimeout(resolve, 1500);
                } catch(e) {
                    resolve();
                }
            });
        }

        async function bunyikanPanggilan(nomor, nama, poli) {
            if (!suaraAktif || !('speechSynthesis' in window)) return;
            if (sedangBerbicara) return;

            sedangBerbicara = true;

            // Force cancel + bersihkan queue
            window.speechSynthesis.cancel();
            await new Promise(r => setTimeout(r, 300));

            // Bunyi notifikasi dulu
            await bunyiNotifikasi();

            // Bersihkan lagi sebelum bicara
            window.speechSynthesis.cancel();
            await new Promise(r => setTimeout(r, 200));

            const pesan = new SpeechSynthesisUtterance(
                `Nomor antrian ${nomor}. ${nama}. Silakan masuk ke ${poli}.`
            );
            pesan.lang   = 'id-ID';
            pesan.rate   = 0.85;
            pesan.pitch  = 1.0;
            pesan.volume = 1.0;

            // Fix Chrome bug: speech berhenti sendiri setelah 15 detik
            const resumeTimer = setInterval(() => {
                if (window.speechSynthesis.speaking) {
                    window.speechSynthesis.resume();
                } else {
                    clearInterval(resumeTimer);
                }
            }, 5000);

            pesan.onend = () => {
                clearInterval(resumeTimer);
                sedangBerbicara = false;
            };

            pesan.onerror = () => {
                clearInterval(resumeTimer);
                sedangBerbicara = false;
            };

            window.speechSynthesis.speak(pesan);
        }

        function pollData() {
            fetch('/sse/antrian')
                .then(r => r.json())
                .then(data => {
                    if (data.sedang_dipanggil) {
                        const d = data.sedang_dipanggil;
                        document.getElementById('nomorPanggil').textContent = d.nomor;
                        document.getElementById('namaPanggil').textContent  = d.nama;
                        document.getElementById('poliPanggil').textContent  = d.poli?.nama ?? '—';
                        if (nomorTerakhir !== d.nomor) {
                            nomorTerakhir = d.nomor;
                            bunyikanPanggilan(d.nomor, d.nama, d.poli?.nama ?? 'ruang dokter');
                        }
                    } else {
                        document.getElementById('nomorPanggil').textContent = '—';
                        document.getElementById('namaPanggil').textContent  = 'Menunggu panggilan...';
                        document.getElementById('poliPanggil').textContent  = '—';
                    }

                    const waiting = data.antrian ?? [];
                    document.getElementById('jumlahWaiting').textContent = waiting.length;
                    const listEl = document.getElementById('listWaiting');
                    if (waiting.length === 0) {
                        listEl.innerHTML = '<li class="empty-state">Tidak ada antrian menunggu</li>';
                    } else {
                        listEl.innerHTML = waiting.slice(0, 8).map(a => `
                            <li>
                                <div class="nomor-badge">${a.nomor}</div>
                                <div class="info">
                                    <div class="nama">${a.nama}</div>
                                    <div class="poli">${a.poli?.nama ?? ''}</div>
                                </div>
                            </li>
                        `).join('');
                    }
                })
                .catch(() => {
                    document.getElementById('statusText').textContent = 'Mencoba reconnect...';
                });
        }

        pollData();
        setInterval(pollData, 1500);
    </script>
</body>
</html>