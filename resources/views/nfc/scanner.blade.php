<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NFC Scanner Absensi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:system-ui,-apple-system,sans-serif}
        .app{min-height:100vh;background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 45%,#0c1a3a 100%);padding:28px 24px;position:relative;overflow:hidden}
        canvas#stars{position:fixed;inset:0;pointer-events:none;z-index:0}
        .glow-orb{position:fixed;border-radius:50%;filter:blur(120px);pointer-events:none;z-index:0}
        .orb-a{width:600px;height:600px;background:rgba(37,99,235,0.18);top:-200px;left:50%;transform:translateX(-50%)}
        .orb-b{width:400px;height:400px;background:rgba(79,70,229,0.12);bottom:-100px;right:-100px}
        .orb-c{width:300px;height:300px;background:rgba(14,165,233,0.1);bottom:100px;left:-80px}

        .wrap{position:relative;z-index:10;max-width:900px;margin:0 auto;display:flex;flex-direction:column;gap:16px}

        /* TOPBAR */
        .topbar{display:flex;align-items:center;justify-content:space-between;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:18px;padding:14px 22px}
        .tb-left{display:flex;align-items:center;gap:12px}
        .tb-logo{width:38px;height:38px;border-radius:12px;background:rgba(59,130,246,0.2);border:1px solid rgba(96,165,250,0.3);display:flex;align-items:center;justify-content:center}
        .tb-logo i{font-size:20px;color:#60a5fa}
        .tb-title{font-size:1rem;font-weight:700;color:#f1f5f9;letter-spacing:.02em}
        .tb-sub{font-size:.72rem;color:#475569;margin-top:1px}
        .tb-right{display:flex;align-items:center;gap:10px}
        .live-pill{display:flex;align-items:center;gap:6px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);border-radius:99px;padding:5px 14px}
        .live-dot{width:7px;height:7px;border-radius:50%;background:#22c55e;animation:livepulse 1.8s infinite}
        @keyframes livepulse{0%,100%{box-shadow:0 0 0 0 rgba(34,197,94,.5)}50%{box-shadow:0 0 0 5px rgba(34,197,94,0)}}
        .live-txt{font-size:.72rem;font-weight:700;color:#4ade80;letter-spacing:.06em;text-transform:uppercase}
        .time-pill{background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:6px 14px;text-align:center}
        .tp-time{font-size:.95rem;font-weight:700;color:#f1f5f9;letter-spacing:.06em;font-variant-numeric:tabular-nums}
        .tp-date{font-size:.65rem;color:#475569;margin-top:1px}

        /* STATS */
        .stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;padding:20px 22px}
        .scard{background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:14px;padding:14px 10px;text-align:center}
        .scard-num{font-size:2rem;font-weight:800;line-height:1;margin-bottom:4px;transition:all .4s}
        .scard-num.blue{color:#60a5fa}
        .scard-num.green{color:#4ade80}
        .scard-num.amber{color:#fbbf24}
        .scard-lbl{font-size:.65rem;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:.08em}

        /* MAIN GRID */
        .main-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        .col-left{display:flex;flex-direction:column;gap:16px}
        .col-right{display:flex;flex-direction:column;gap:16px}

        /* CARD */
        .card{background:rgba(15,28,60,0.65);border:1px solid rgba(96,165,250,0.1);border-radius:20px;backdrop-filter:blur(20px)}

        /* SCANNER */
        .scanner-card{padding:28px 24px;text-align:center;position:relative;overflow:hidden}
        .scanner-card::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 50% 0%,rgba(59,130,246,0.08) 0%,transparent 70%);pointer-events:none}
        .scanner-stage{position:relative;height:140px;display:flex;align-items:center;justify-content:center;margin-bottom:20px}
        .pulse-ring{position:absolute;border-radius:50%;border:1.5px solid rgba(96,165,250,0.2);animation:ringout 2.8s ease-out infinite}
        .pr1{width:60px;height:60px;animation-delay:0s}
        .pr2{width:96px;height:96px;animation-delay:.7s}
        .pr3{width:132px;height:132px;animation-delay:1.4s}
        @keyframes ringout{0%{opacity:.8;transform:scale(.5)}100%{opacity:0;transform:scale(1.15)}}
        .nfc-hub{width:56px;height:56px;border-radius:50%;background:rgba(37,99,235,0.25);border:2px solid rgba(96,165,250,0.5);display:flex;align-items:center;justify-content:center;z-index:5;position:relative}
        .nfc-hub i{font-size:26px;color:#93c5fd;animation:hubbob 3s ease-in-out infinite}
        @keyframes hubbob{0%,100%{transform:scale(1)}50%{transform:scale(1.12)}}
        .scan-title{font-size:1.35rem;font-weight:700;color:#f1f5f9;margin-bottom:6px}
        .scan-sub{font-size:.82rem;color:#475569}
        .progress-wrap{margin:16px 0 0;height:4px;background:rgba(255,255,255,0.06);border-radius:99px;overflow:hidden}
        .progress-bar{height:100%;width:0%;background:rgba(96,165,250,0.7);border-radius:99px;transition:width .3s ease}
        .status-row-inner{display:flex;align-items:center;gap:10px;margin-top:14px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:12px 14px}
        .sdot{width:9px;height:9px;border-radius:50%;background:#ef4444;flex-shrink:0;transition:background .3s}
        .sdot.on{background:#22c55e;animation:livepulse 1.5s infinite}
        .sdot.proc{background:#f59e0b}
        .stxt{flex:1;font-size:.84rem;color:#64748b;transition:color .2s}
        .stxt.on{color:#4ade80}
        .stxt.proc{color:#fbbf24}
        .slabel{font-size:.68rem;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:.08em}

        /* MATKUL */
        .matkul-card{padding:20px 22px}
        .card-label{font-size:.68rem;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:.1em;margin-bottom:10px;display:flex;align-items:center;gap:6px}
        .card-label i{font-size:14px;color:#3b82f6}
        .sel-wrap{position:relative}
        select.sel{width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(96,165,250,0.18);border-radius:14px;padding:13px 44px 13px 16px;font-size:.9rem;color:#e2e8f0;outline:none;appearance:none;cursor:pointer;transition:all .2s;font-family:inherit}
        select.sel:focus{border-color:rgba(96,165,250,0.5);background:rgba(59,130,246,0.07)}
        option{background:#0d1b38;color:#e2e8f0}
        .sel-icon{position:absolute;right:14px;top:50%;transform:translateY(-50%);pointer-events:none;color:#475569;font-size:18px}

        /* BTN */
        .btn-main{width:100%;border:none;border-radius:16px;padding:17px;font-size:1rem;font-weight:800;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;transition:all .2s;letter-spacing:.02em;background:#1d4ed8;color:#fff;position:relative;overflow:hidden}
        .btn-main:hover{background:#2563eb;transform:translateY(-2px);box-shadow:0 12px 32px rgba(37,99,235,0.35)}
        .btn-main:active{transform:scale(.98)}
        .btn-main:disabled{background:rgba(37,99,235,0.25);color:rgba(255,255,255,0.35);cursor:not-allowed;transform:none;box-shadow:none}
        .btn-main i{font-size:22px}

        /* CHART */
        .chart-card{padding:20px 22px}
        .chart-title{font-size:.7rem;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:.1em;margin-bottom:16px;display:flex;align-items:center;gap:6px}
        .chart-title i{font-size:14px;color:#3b82f6}
        .chart-bars{display:flex;align-items:flex-end;gap:12px;height:90px}
        .bar-wrap{flex:1;display:flex;flex-direction:column;align-items:center;gap:5px}
        .bar{width:100%;border-radius:7px 7px 0 0;transition:height .6s cubic-bezier(.23,1,.32,1);min-height:4px}
        .bar:hover{filter:brightness(1.25);cursor:default}
        .bar.b-green{background:rgba(34,197,94,0.65)}
        .bar.b-amber{background:rgba(245,158,11,0.65)}
        .bar.b-red{background:rgba(239,68,68,0.65)}
        .bar-val{font-size:.75rem;font-weight:700;color:#94a3b8}
        .bar-lbl{font-size:.65rem;color:#334155;text-align:center;white-space:nowrap;font-weight:600}

        /* RESULT */
        .result-card{overflow:hidden;display:none;animation:popIn .35s cubic-bezier(.175,.885,.32,1.275)}
        .result-card.show{display:block}
        @keyframes popIn{from{opacity:0;transform:scale(.93)}to{opacity:1;transform:scale(1)}}
        .result-top{padding:22px 22px 16px;display:flex;align-items:center;gap:16px}
        .rav{width:62px;height:62px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .rav.s{background:rgba(16,185,129,0.15);border:2px solid rgba(52,211,153,0.3)}
        .rav.w{background:rgba(245,158,11,0.15);border:2px solid rgba(251,191,36,0.3)}
        .rav.d{background:rgba(239,68,68,0.15);border:2px solid rgba(252,165,165,0.3)}
        .rinfo{flex:1;min-width:0}
        .rname{font-size:1.1rem;font-weight:800;color:#f1f5f9;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .rnim{font-size:.8rem;color:#64748b;margin-top:3px}
        .rbadge{display:inline-flex;align-items:center;gap:5px;margin-top:7px;padding:5px 14px;border-radius:99px;font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em}
        .rb-s{background:rgba(16,185,129,0.15);color:#4ade80;border:1px solid rgba(52,211,153,0.2)}
        .rb-w{background:rgba(245,158,11,0.15);color:#fcd34d;border:1px solid rgba(251,191,36,0.2)}
        .rb-d{background:rgba(239,68,68,0.15);color:#fca5a5;border:1px solid rgba(252,165,165,0.2)}
        .rmeta{display:flex;border-top:1px solid rgba(255,255,255,0.06)}
        .rmeta-item{flex:1;padding:12px 16px;text-align:center;border-right:1px solid rgba(255,255,255,0.06);display:flex;flex-direction:column;align-items:center;gap:3px}
        .rmeta-item:last-child{border-right:none}
        .rmeta-icon{font-size:15px;color:#334155}
        .rmeta-val{font-size:.82rem;font-weight:700;color:#cbd5e1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100px}
        .rmeta-lbl{font-size:.62rem;color:#1e293b;text-transform:uppercase;letter-spacing:.07em}

        /* HISTORY */
        .hist-card{padding:20px 22px;flex:1}
        .hist-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
        .hist-title{font-size:.7rem;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:.1em;display:flex;align-items:center;gap:6px}
        .hist-title i{font-size:14px;color:#3b82f6}
        .hist-clear{font-size:.68rem;color:#1e293b;cursor:pointer;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:4px 12px;transition:all .2s}
        .hist-clear:hover{color:#f87171;border-color:rgba(239,68,68,0.3);background:rgba(239,68,68,0.05)}
        .hist-empty{text-align:center;padding:28px 0;display:flex;flex-direction:column;align-items:center;gap:8px;color:#1e293b;font-size:.82rem}
        .hist-empty i{font-size:32px;opacity:.4}
        .hist-list{display:flex;flex-direction:column;gap:7px}
        .hi{display:flex;align-items:center;gap:10px;padding:10px 14px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:13px;animation:slideIn .3s ease}
        @keyframes slideIn{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}
        .hi-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
        .hi-dot.s{background:#22c55e}
        .hi-dot.w{background:#f59e0b}
        .hi-dot.d{background:#ef4444}
        .hi-name{flex:1;font-size:.84rem;color:#cbd5e1;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .hi-nim{font-size:.72rem;color:#334155;margin-top:1px}
        .hi-mk{font-size:.7rem;color:#1e293b}
        .hi-time{font-size:.72rem;color:#1e293b;flex-shrink:0}
        .hi-badge{font-size:.65rem;font-weight:800;padding:3px 9px;border-radius:99px;flex-shrink:0;text-transform:uppercase;letter-spacing:.04em}
        .hb-s{background:rgba(34,197,94,.12);color:#4ade80}
        .hb-w{background:rgba(245,158,11,.12);color:#fbbf24}
        .hb-d{background:rgba(239,68,68,.12);color:#f87171}

        /* FOOTER */
        .footer{text-align:center;padding:4px 0 8px;display:flex;align-items:center;justify-content:center;gap:8px;color:#1e293b;font-size:.72rem}
        .footer i{font-size:14px}

        @keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}
        @keyframes countUp{from{transform:scale(1.3);opacity:.5}to{transform:scale(1);opacity:1}}
        .count-anim{animation:countUp .35s ease}
    </style>
</head>
<body>
<div class="app">
    <canvas id="stars"></canvas>
    <div class="glow-orb orb-a"></div>
    <div class="glow-orb orb-b"></div>
    <div class="glow-orb orb-c"></div>

    <div class="wrap">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="tb-left">
                <div class="tb-logo"><i class="ti ti-access-point" aria-hidden="true"></i></div>
                <div>
                    <div class="tb-title">NFC Absensi</div>
                    <div class="tb-sub">Sistem Absensi Digital</div>
                </div>
            </div>
            <div class="tb-right">
                <div class="live-pill">
                    <div class="live-dot"></div>
                    <span class="live-txt">Online</span>
                </div>
                <div class="time-pill">
                    <div class="tp-time" id="tp-time">--:--:--</div>
                    <div class="tp-date" id="tp-date">Memuat...</div>
                </div>
            </div>
        </div>

        {{-- STATS --}}
        <div class="card" style="padding:0">
            <div class="stats-grid">
                <div class="scard">
                    <div class="scard-num blue" id="s-total">0</div>
                    <div class="scard-lbl">Total Scan</div>
                </div>
                <div class="scard">
                    <div class="scard-num green" id="s-hadir">0</div>
                    <div class="scard-lbl">Hadir</div>
                </div>
                <div class="scard">
                    <div class="scard-num amber" id="s-terlambat">0</div>
                    <div class="scard-lbl">Terlambat</div>
                </div>
            </div>
        </div>

        {{-- MAIN GRID --}}
        <div class="main-grid">

            {{-- KOLOM KIRI --}}
            <div class="col-left">

                {{-- SCANNER --}}
                <div class="card scanner-card">
                    <div class="scanner-stage">
                        <div class="pulse-ring pr1"></div>
                        <div class="pulse-ring pr2"></div>
                        <div class="pulse-ring pr3"></div>
                        <div class="nfc-hub">
                            <i class="ti ti-access-point" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="scan-title">NFC Scanner</div>
                    <div class="scan-sub">Dekatkan kartu mahasiswa ke perangkat</div>
                    <div class="progress-wrap">
                        <div class="progress-bar" id="pbar"></div>
                    </div>
                    <div class="status-row-inner">
                        <div class="sdot" id="sdot"></div>
                        <div>
                            <div class="slabel">Status Scanner</div>
                            <div class="stxt" id="smsg">NFC belum aktif</div>
                        </div>
                    </div>
                </div>

                {{-- MATKUL --}}
                <div class="card matkul-card">
                    <div class="card-label">
                        <i class="ti ti-books" aria-hidden="true"></i>
                        Pilih Mata Kuliah
                    </div>
                    <div class="sel-wrap">
                        <select class="sel" id="mata_kuliah">
                            <option value="">— Pilih Mata Kuliah —</option>
                            <option value="Kecerdasan Buatan">Kecerdasan Buatan</option>
                            <option value="Kecerdasan Buatan (Praktikum)">Kecerdasan Buatan (Praktikum)</option>
                            <option value="Aplikasi Mobile">Aplikasi Mobile</option>
                            <option value="Aplikasi Mobile (Praktikum)">Aplikasi Mobile (Praktikum)</option>
                            <option value="Pengujian Perangkat Lunak">Pengujian Perangkat Lunak</option>
                            <option value="Pengujian Perangkat Lunak (Praktikum)">Pengujian Perangkat Lunak (Praktikum)</option>
                            <option value="Manajemen Proyek Perangkat Lunak">Manajemen Proyek Perangkat Lunak</option>
                            <option value="Manajemen Proyek Perangkat Lunak (Praktikum)">Manajemen Proyek Perangkat Lunak (Praktikum)</option>
                            <option value="Workshop Desain UI">Workshop Desain UI</option>
                            <option value="Workshop Pengembangan Perangkat Lunak WEB (Framework) Praktikum">Workshop PPL WEB (Framework) Praktikum</option>
                        </select>
                        <i class="ti ti-chevron-down sel-icon" aria-hidden="true"></i>
                    </div>
                </div>

                {{-- TOMBOL --}}
                <button class="btn-main" id="btn-scan" onclick="startScan()">
                    <i class="ti ti-access-point" id="btn-icon" aria-hidden="true"></i>
                    <span id="btn-text">Aktifkan NFC Scanner</span>
                </button>

                {{-- GRAFIK --}}
                <div class="card chart-card">
                    <div class="chart-title">
                        <i class="ti ti-chart-bar" aria-hidden="true"></i>
                        Grafik Sesi Ini
                    </div>
                    <div class="chart-bars">
                        <div class="bar-wrap">
                            <div class="bar b-green" id="cb-hadir" style="height:4px"></div>
                            <div class="bar-val" id="cv-hadir">0</div>
                            <div class="bar-lbl">Hadir</div>
                        </div>
                        <div class="bar-wrap">
                            <div class="bar b-amber" id="cb-terlambat" style="height:4px"></div>
                            <div class="bar-val" id="cv-terlambat">0</div>
                            <div class="bar-lbl">Terlambat</div>
                        </div>
                        <div class="bar-wrap">
                            <div class="bar b-red" id="cb-tidakhadir" style="height:4px"></div>
                            <div class="bar-val" id="cv-tidakhadir">0</div>
                            <div class="bar-lbl">Tidak Hadir</div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN --}}
            <div class="col-right">

                {{-- HASIL SCAN --}}
                <div class="card result-card" id="hasil-box">
                    <div class="result-top">
                        <div class="rav s" id="r-av">
                            <i class="ti ti-user" id="r-icon" style="font-size:26px;color:#34d399"></i>
                        </div>
                        <div class="rinfo">
                            <div class="rname" id="r-nama">—</div>
                            <div class="rnim" id="r-nim"></div>
                            <div class="rbadge rb-s" id="r-badge">
                                <i class="ti ti-check" style="font-size:11px"></i> Hadir
                            </div>
                        </div>
                    </div>
                    <div class="rmeta">
                        <div class="rmeta-item">
                            <i class="ti ti-clock rmeta-icon" aria-hidden="true"></i>
                            <div class="rmeta-val" id="r-jam">—</div>
                            <div class="rmeta-lbl">Waktu</div>
                        </div>
                        <div class="rmeta-item">
                            <i class="ti ti-book rmeta-icon" aria-hidden="true"></i>
                            <div class="rmeta-val" id="r-mk" style="font-size:.74rem">—</div>
                            <div class="rmeta-lbl">Matkul</div>
                        </div>
                        <div class="rmeta-item">
                            <i class="ti ti-message rmeta-icon" aria-hidden="true"></i>
                            <div class="rmeta-val" id="r-msg" style="font-size:.74rem">—</div>
                            <div class="rmeta-lbl">Info</div>
                        </div>
                    </div>
                </div>

                {{-- RIWAYAT --}}
                <div class="card hist-card">
                    <div class="hist-head">
                        <div class="hist-title">
                            <i class="ti ti-history" aria-hidden="true"></i>
                            Riwayat Scan
                        </div>
                        <div class="hist-clear" onclick="clearAll()">Hapus semua</div>
                    </div>
                    <div class="hist-list" id="hist-list">
                        <div class="hist-empty">
                            <i class="ti ti-wave-sine" aria-hidden="true"></i>
                            Belum ada scan hari ini
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="footer">
            <i class="ti ti-shield-lock" aria-hidden="true"></i>
            Sistem Absensi Digital &middot; NFC Technology &middot; 2025
        </div>

    </div>
</div>

<script>
    const CSRF = document.querySelector('meta[name=csrf-token]').content;
    let ndefReader = null;
    let counts = { total: 0, hadir: 0, terlambat: 0, tidakhadir: 0 };
    let hist = [];

    function pad(n) { return String(n).padStart(2, '0'); }

    function tick() {
        const now = new Date();
        document.getElementById('tp-time').textContent =
            `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
        document.getElementById('tp-date').textContent =
            now.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
    }
    tick();
    setInterval(tick, 1000);

    function setStat(id, val) {
        const el = document.getElementById(id);
        el.textContent = val;
        el.classList.remove('count-anim');
        void el.offsetWidth;
        el.classList.add('count-anim');
    }

    function setStatus(state, msg) {
        const dot = document.getElementById('sdot');
        const txt = document.getElementById('smsg');
        dot.className = 'sdot' + (state === 'on' ? ' on' : state === 'proc' ? ' proc' : '');
        txt.className = 'stxt' + (state === 'on' ? ' on' : state === 'proc' ? ' proc' : '');
        txt.textContent = msg;
    }

    function runBar() {
        const bar = document.getElementById('pbar');
        bar.style.transition = 'width .9s ease';
        bar.style.width = '80%';
        setTimeout(() => {
            bar.style.transition = 'width .2s';
            bar.style.width = '100%';
            setTimeout(() => {
                bar.style.transition = 'width .5s ease';
                bar.style.width = '0%';
            }, 300);
        }, 900);
    }

    function updateChart() {
        const maxH = 86;
        const max  = Math.max(counts.hadir, counts.terlambat, counts.tidakhadir, 1);
        const keys = ['hadir', 'terlambat', 'tidakhadir'];
        keys.forEach(k => {
            document.getElementById('cb-' + k).style.height = Math.max((counts[k] / max) * maxH, 4) + 'px';
            document.getElementById('cv-' + k).textContent = counts[k];
        });
    }

    async function startScan() {
        if (!('NDEFReader' in window)) {
            setStatus('', 'Browser tidak mendukung Web NFC. Gunakan Android Chrome.');
            return;
        }
        const mk = document.getElementById('mata_kuliah').value.trim();
        if (!mk) { alert('Pilih mata kuliah terlebih dahulu!'); return; }

        try {
            ndefReader = new NDEFReader();
            await ndefReader.scan();
            setStatus('on', 'NFC aktif — dekatkan kartu mahasiswa...');
            document.getElementById('btn-icon').style.animation = 'spin 1s linear infinite';
            document.getElementById('btn-text').textContent = 'Scanning...';
            document.getElementById('btn-scan').disabled = true;

            ndefReader.addEventListener('reading', async ({ serialNumber }) => {
                await prosesAbsensi(serialNumber, mk);
            });
            ndefReader.addEventListener('readingerror', () => {
                setStatus('', 'Gagal membaca kartu. Coba lagi.');
            });
        } catch (err) {
            setStatus('', 'Error: ' + err.message);
            resetBtn();
        }
    }

    async function prosesAbsensi(sn, mk) {
        setStatus('proc', 'Memproses kartu...');
        runBar();
        try {
            const res  = await fetch('/nfc/scan', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ serial_number: sn, mata_kuliah: mk })
            });
            const data = await res.json();

            showResult(data, mk);
            addHist(data, mk);

            counts.total++;
            if (data.status === 'hadir')       counts.hadir++;
            else if (data.status === 'terlambat') counts.terlambat++;
            else                                counts.tidakhadir++;

            setStat('s-total',    counts.total);
            setStat('s-hadir',    counts.hadir);
            setStat('s-terlambat', counts.terlambat);
            updateChart();

            setTimeout(() => {
                document.getElementById('hasil-box').style.display = 'none';
                setStatus('on', 'Siap scan berikutnya...');
            }, 5000);

        } catch (err) {
            setStatus('', 'Gagal koneksi ke server.');
        }
    }

    function showResult(data, mk) {
        const box   = document.getElementById('hasil-box');
        const av    = document.getElementById('r-av');
        const badge = document.getElementById('r-badge');
        const icon  = document.getElementById('r-icon');

        box.style.display = 'block';
        box.className = 'card result-card show';

        if (data.status === 'hadir') {
            av.className = 'rav s';
            icon.className = 'ti ti-check'; icon.style.color = '#34d399';
            badge.className = 'rbadge rb-s';
            badge.innerHTML = '<i class="ti ti-check" style="font-size:11px"></i> Hadir';
        } else if (data.status === 'terlambat') {
            av.className = 'rav w';
            icon.className = 'ti ti-clock-exclamation'; icon.style.color = '#fbbf24';
            badge.className = 'rbadge rb-w';
            badge.innerHTML = '<i class="ti ti-clock" style="font-size:11px"></i> Terlambat';
        } else if (data.status === 'duplikat') {
            av.className = 'rav w';
            icon.className = 'ti ti-repeat'; icon.style.color = '#fbbf24';
            badge.className = 'rbadge rb-w';
            badge.innerHTML = '<i class="ti ti-repeat" style="font-size:11px"></i> Sudah Absen';
        } else {
            av.className = 'rav d';
            icon.className = 'ti ti-user-x'; icon.style.color = '#f87171';
            badge.className = 'rbadge rb-d';
            badge.innerHTML = '<i class="ti ti-x" style="font-size:11px"></i> Tidak Terdaftar';
        }

        icon.style.fontSize = '26px';
        document.getElementById('r-nama').textContent = data.nama    ?? 'Tidak Dikenal';
        document.getElementById('r-nim').textContent  = data.nim     ?? '';
        document.getElementById('r-jam').textContent  = data.jam     ?? '—';
        document.getElementById('r-mk').textContent   = mk;
        document.getElementById('r-msg').textContent  = data.message ?? '—';
    }

    function addHist(data, mk) {
        const now = new Date();
        const t   = `${pad(now.getHours())}:${pad(now.getMinutes())}`;
        hist.unshift({ nama: data.nama ?? 'Tidak Dikenal', nim: data.nim ?? '', status: data.status, mk, t });
        if (hist.length > 10) hist.pop();
        renderHist();
    }

    function renderHist() {
        const el = document.getElementById('hist-list');
        if (!hist.length) {
            el.innerHTML = '<div class="hist-empty"><i class="ti ti-wave-sine" aria-hidden="true"></i>Belum ada scan hari ini</div>';
            return;
        }
        el.innerHTML = hist.map(h => {
            const dc  = h.status === 'hadir' ? 's' : h.status === 'terlambat' ? 'w' : 'd';
            const bc  = h.status === 'hadir' ? 'hb-s' : h.status === 'terlambat' ? 'hb-w' : 'hb-d';
            const lb  = h.status === 'hadir' ? 'Hadir' : h.status === 'terlambat' ? 'Terlambat' : h.status === 'duplikat' ? 'Duplikat' : 'Tidak Hadir';
            const mkS = h.mk.length > 22 ? h.mk.slice(0, 22) + '…' : h.mk;
            return `<div class="hi">
                <div class="hi-dot ${dc}"></div>
                <div style="flex:1;min-width:0">
                    <div class="hi-name">${h.nama}</div>
                    <div class="hi-nim">${h.nim} &middot; <span class="hi-mk">${mkS}</span></div>
                </div>
                <div class="hi-time">${h.t}</div>
                <div class="hi-badge ${bc}">${lb}</div>
            </div>`;
        }).join('');
    }

    function clearAll() {
        hist   = [];
        counts = { total: 0, hadir: 0, terlambat: 0, tidakhadir: 0 };
        renderHist();
        updateChart();
        setStat('s-total', 0);
        setStat('s-hadir', 0);
        setStat('s-terlambat', 0);
    }

    function resetBtn() {
        document.getElementById('btn-icon').style.animation = '';
        document.getElementById('btn-text').textContent = 'Aktifkan NFC Scanner';
        document.getElementById('btn-scan').disabled = false;
    }

    /* ── Starfield Background ── */
    (function () {
        const c   = document.getElementById('stars');
        const ctx = c.getContext('2d');
        let W, H, stars = [];

        function resize() {
            W = c.width  = window.innerWidth;
            H = c.height = window.innerHeight;
            stars = [];
            for (let i = 0; i < 120; i++) {
                stars.push({
                    x:  Math.random() * W,
                    y:  Math.random() * H,
                    r:  Math.random() * 1.2 + .2,
                    a:  Math.random() * .7 + .1,
                    vx: (Math.random() - .5) * .15,
                    vy: (Math.random() - .5) * .15,
                    tw: Math.random() * 3 + 1
                });
            }
        }
        resize();
        window.addEventListener('resize', resize);

        let t = 0;
        function draw() {
            ctx.clearRect(0, 0, W, H);
            t += .02;
            stars.forEach(s => {
                const alpha = s.a * (0.5 + 0.5 * Math.sin(t / s.tw));
                ctx.beginPath();
                ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(148,197,255,${alpha})`;
                ctx.fill();
                s.x += s.vx; s.y += s.vy;
                if (s.x < 0 || s.x > W) s.vx *= -1;
                if (s.y < 0 || s.y > H) s.vy *= -1;
            });
            for (let i = 0; i < stars.length; i++) {
                for (let j = i + 1; j < stars.length; j++) {
                    const dx = stars[i].x - stars[j].x;
                    const dy = stars[i].y - stars[j].y;
                    const d  = Math.sqrt(dx * dx + dy * dy);
                    if (d < 80) {
                        ctx.beginPath();
                        ctx.moveTo(stars[i].x, stars[i].y);
                        ctx.lineTo(stars[j].x, stars[j].y);
                        ctx.strokeStyle = `rgba(96,165,250,${.06 * (1 - d / 80)})`;
                        ctx.lineWidth   = .4;
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(draw);
        }
        draw();
    })();
</script>
</body>
</html>