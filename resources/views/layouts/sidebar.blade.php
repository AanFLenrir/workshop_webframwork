<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        {{-- PROFILE --}}
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile">
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ auth()->user()->name }}</span>
                    <span class="text-secondary text-small">{{ auth()->user()->role }}</span>
                </div>
            </a>
        </li>

        {{-- DASHBOARD --}}
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        {{-- KATEGORI --}}
        <li class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kategori.index') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-shape menu-icon"></i>
            </a>
        </li>

        {{-- BUKU --}}
        <li class="nav-item {{ request()->routeIs('buku.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buku.index') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book-open-page-variant menu-icon"></i>
            </a>
        </li>

        {{-- SERTIFIKAT --}}
        <li class="nav-item {{ request()->routeIs('pdf.sertifikat') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pdf.sertifikat') }}" target="_blank">
                <span class="menu-title">Sertifikat</span>
                <i class="mdi mdi-certificate-outline menu-icon"></i>
            </a>
        </li>

        {{-- UNDANGAN --}}
        <li class="nav-item {{ request()->routeIs('pdf.undangan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pdf.undangan') }}" target="_blank">
                <span class="menu-title">Undangan</span>
                <i class="mdi mdi-email-outline menu-icon"></i>
            </a>
        </li>

        {{-- TAG HARGA + BARCODE READER --}}
        <li class="nav-item {{ request()->routeIs('barang.*') || request()->routeIs('barcode.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#menuTagHarga"
               aria-expanded="{{ request()->routeIs('barang.*') || request()->routeIs('barcode.*') ? 'true' : 'false' }}">
                <span class="menu-title">Tag Harga</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-tag-outline menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('barang.*') || request()->routeIs('barcode.*') ? 'show' : '' }}" id="menuTagHarga">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}"
                           href="{{ route('barang.index') }}">Cetak Tag Harga</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barcode.reader') ? 'active' : '' }}"
                           href="{{ route('barcode.reader') }}">Barcode Reader</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- TABEL BIASA --}}
        <li class="nav-item {{ request()->routeIs('js.tabel_biasa') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('js.tabel_biasa') }}">
                <span class="menu-title">Tabel Biasa</span>
                <i class="mdi mdi-table menu-icon"></i>
            </a>
        </li>

        {{-- TABEL DATATABLES --}}
        <li class="nav-item {{ request()->routeIs('js.tabel_datatables') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('js.tabel_datatables') }}">
                <span class="menu-title">Tabel DataTables</span>
                <i class="mdi mdi-table-search menu-icon"></i>
            </a>
        </li>

        {{-- SELECT & SELECT2 --}}
        <li class="nav-item {{ request()->routeIs('js.select') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('js.select') }}">
                <span class="menu-title">Select & Select2</span>
                <i class="mdi mdi-form-select menu-icon"></i>
            </a>
        </li>

        {{-- WILAYAH AJAX --}}
        <li class="nav-item {{ request()->routeIs('js.wilayah_ajax') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('js.wilayah_ajax') }}">
                <span class="menu-title">Wilayah Ajax</span>
                <i class="mdi mdi-map-marker menu-icon"></i>
            </a>
        </li>

        {{-- WILAYAH AXIOS --}}
        <li class="nav-item {{ request()->routeIs('js.wilayah_axios') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('js.wilayah_axios') }}">
                <span class="menu-title">Wilayah Axios</span>
                <i class="mdi mdi-map-marker-outline menu-icon"></i>
            </a>
        </li>

        {{-- POS / KASIR --}}
        <li class="nav-item {{ request()->routeIs('pos.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pos.index') }}">
                <span class="menu-title">Point of Sales</span>
                <i class="mdi mdi-cash-register menu-icon"></i>
            </a>
        </li>

        {{-- RIWAYAT TRANSAKSI --}}
        <li class="nav-item {{ request()->routeIs('pos.riwayat') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pos.riwayat') }}">
                <span class="menu-title">Riwayat Transaksi</span>
                <i class="mdi mdi-history menu-icon"></i>
            </a>
        </li>

        {{-- VENDOR SCAN QR --}}
        <li class="nav-item {{ request()->routeIs('vendor.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('vendor.scan') }}">
                <span class="menu-title">Vendor Scan QR</span>
                <i class="mdi mdi-qrcode-scan menu-icon"></i>
            </a>
        </li>

        {{-- KUNJUNGAN TOKO --}}
        <li class="nav-item {{ request()->routeIs('toko.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#menuToko"
               aria-expanded="{{ request()->routeIs('toko.*') ? 'true' : 'false' }}">
                <span class="menu-title">Kunjungan Toko</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-store menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('toko.*') ? 'show' : '' }}" id="menuToko">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toko.index') ? 'active' : '' }}"
                           href="{{ route('toko.index') }}">Data Toko</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toko.kunjungan') ? 'active' : '' }}"
                           href="{{ route('toko.kunjungan') }}">Kunjungan Toko</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- CUSTOMER --}}
        <li class="nav-item {{ request()->routeIs('customer.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#menuCustomer"
               aria-expanded="{{ request()->routeIs('customer.*') ? 'true' : 'false' }}">
                <span class="menu-title">Customer</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-account-group menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('customer.*') ? 'show' : '' }}" id="menuCustomer">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.index') ? 'active' : '' }}"
                           href="{{ route('customer.index') }}">Data Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.create1') ? 'active' : '' }}"
                           href="{{ route('customer.create1') }}">Tambah Customer 1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.create2') ? 'active' : '' }}"
                           href="{{ route('customer.create2') }}">Tambah Customer 2</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- ANTRIAN --}}
        <li class="nav-item {{ request()->is('guest*') || request()->is('admin*') || request()->is('papan*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#menuAntrian"
               aria-expanded="{{ request()->is('guest*') || request()->is('admin*') || request()->is('papan*') ? 'true' : 'false' }}">
                <span class="menu-title">Antrian</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-ticket-confirmation-outline menu-icon"></i>
            </a>
            <div class="collapse {{ request()->is('guest*') || request()->is('admin*') || request()->is('papan*') ? 'show' : '' }}" id="menuAntrian">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('guest') ? 'active' : '' }}" href="/guest">
                            Daftar Antrian (Guest)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin*') ? 'active' : '' }}" href="/admin">
                            Admin Antrian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('papan') ? 'active' : '' }}" href="/papan" target="_blank">
                            Papan Antrian
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- NFC ABSENSI --}}
        <li class="nav-item {{ request()->routeIs('nfc.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#menuNfc"
               aria-expanded="{{ request()->routeIs('nfc.*') ? 'true' : 'false' }}">
                <span class="menu-title">NFC Absensi</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-card-account-details-outline menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('nfc.*') ? 'show' : '' }}" id="menuNfc">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('nfc.index') ? 'active' : '' }}"
                           href="{{ route('nfc.index') }}">Data Kartu NFC</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('nfc.rekap') ? 'active' : '' }}"
                           href="{{ route('nfc.rekap') }}">Rekap Absensi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('nfc.scanner') ? 'active' : '' }}"
                           href="{{ route('nfc.scanner') }}" target="_blank">Scanner NFC</a>
                    </li>
                </ul>
            </div>
        </li>

    </ul>
</nav>