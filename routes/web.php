<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BarcodeReaderController;
use App\Http\Controllers\VendorScanController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PapanController;
use App\Http\Controllers\SSEController;
use App\Http\Controllers\NfcController;

// =====================
// AUTH
// =====================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('/verifikasi-otp', function () {
    return view('auth.otp');
})->name('otp.form');
Route::post('/verifikasi-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');

// =====================
// ANTRIAN (tanpa auth — akses publik)
// =====================
Route::get('/guest', [GuestController::class, 'index']);
Route::post('/guest/daftar', [GuestController::class, 'daftar']);
Route::get('/tiket/{antrian}', [GuestController::class, 'tiket']);
Route::get('/papan', [PapanController::class, 'index']);
Route::get('/sse/antrian', [SSEController::class, 'semua']);
Route::get('/sse/antrian/{kode}', [SSEController::class, 'poli']);

// =====================
// NFC Scanner (tanpa auth — akses dari HP)
// =====================
Route::get('/nfc/scanner', [NfcController::class, 'scanner'])->name('nfc.scanner');
Route::post('/nfc/scan', [NfcController::class, 'scan'])->name('nfc.scan');

// =====================
// PROTECTED ROUTES
// =====================
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/profile', fn() => view('profile'))->name('profile');

    // -------------------------
    // Barang
    // -------------------------
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::post('/barang/cetak-pdf', [BarangController::class, 'cetakPdf'])->name('barang.cetakPdf');
    Route::post('/barang/cetak-pdf-barcode', [BarangController::class, 'cetakPdfBarcode'])->name('barang.cetakPdfBarcode');

    // -------------------------
    // Kategori
    // -------------------------
    Route::resource('kategori', KategoriController::class)->except('show');

    // -------------------------
    // Buku
    // -------------------------
    Route::resource('buku', BukuController::class)->except('show');

    // -------------------------
    // POS
    // -------------------------
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/bayar', [PosController::class, 'bayar'])->name('pos.bayar');
    Route::post('/pos/cari-barang', [PosController::class, 'cariBarang'])->name('pos.cari');
    Route::get('/pos/riwayat', [PosController::class, 'riwayat'])->name('pos.riwayat');

    // -------------------------
    // QR Code Generator
    // -------------------------
    Route::get('/qrcode/{order_code}', function ($order_code) {
        return response(
            \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($order_code)
        )->header('Content-Type', 'image/svg+xml');
    })->name('qrcode.generate');

    // -------------------------
    // PDF
    // -------------------------
    Route::get('/pdf/sertifikat', [PdfController::class, 'sertifikat'])->name('pdf.sertifikat');
    Route::get('/pdf/undangan', [PdfController::class, 'undangan'])->name('pdf.undangan');

    // -------------------------
    // Customer (SC3 - Modul Kamera)
    // -------------------------
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/tambah-1', [CustomerController::class, 'create1'])->name('customer.create1');
    Route::post('/customer/tambah-1', [CustomerController::class, 'store1'])->name('customer.store1');
    Route::get('/customer/tambah-2', [CustomerController::class, 'create2'])->name('customer.create2');
    Route::post('/customer/tambah-2', [CustomerController::class, 'store2'])->name('customer.store2');

    // -------------------------
    // Barcode Reader (Praktikum 1)
    // -------------------------
    Route::get('/barcode-reader', [BarcodeReaderController::class, 'index'])->name('barcode.reader');
    Route::get('/barcode-reader/cari/{kode}', [BarcodeReaderController::class, 'cariBarang'])->name('barcode.cari');

    // -------------------------
    // QR Code Reader - Customer (Praktikum 2)
    // -------------------------
    Route::get('/pos/sukses/{id_pesanan}', [PosController::class, 'paymentSuccess'])->name('pos.sukses');
    Route::get('/pesanan/{id}/qrcode', [PosController::class, 'lihatQrCode'])->name('pesanan.qrcode');

    // -------------------------
    // QR Code Reader - Vendor (Praktikum 2)
    // -------------------------
    Route::get('/vendor/scan-qr', [VendorScanController::class, 'index'])->name('vendor.scan');
    Route::get('/vendor/scan-qr/cek/{id_pesanan}', [VendorScanController::class, 'cekPesanan'])->name('vendor.cekPesanan');

    // -------------------------
    // Kunjungan Toko - Geolocation
    // -------------------------
    Route::get('/toko', [TokoController::class, 'index'])->name('toko.index');
    Route::post('/toko', [TokoController::class, 'store'])->name('toko.store');
    Route::get('/toko/kunjungan', [TokoController::class, 'kunjungan'])->name('toko.kunjungan');
    Route::get('/toko/cari/{barcode}', [TokoController::class, 'cariToko'])->name('toko.cari');
    Route::post('/toko/kunjungan/simpan', [TokoController::class, 'simpanKunjungan'])->name('toko.simpanKunjungan');
    Route::get('/toko/{id}/barcode', [TokoController::class, 'cetakBarcode'])->name('toko.barcode');

    // -------------------------
    // JS Demo Pages
    // -------------------------
    Route::view('/js-select', 'js.select')->name('js.select');
    Route::view('/js-tabel-biasa', 'js.tabel_biasa')->name('js.tabel_biasa');
    Route::view('/js-tabel-datatables', 'js.tabel_datatables')->name('js.tabel_datatables');
    Route::view('/js-wilayah-ajax', 'js.wilayah_ajax')->name('js.wilayah_ajax');
    Route::view('/js-wilayah-axios', 'js.wilayah_axios')->name('js.wilayah_axios');

    // -------------------------
    // Wilayah API
    // -------------------------
    Route::get('/api/provinsi', [WilayahController::class, 'provinsi'])->name('api.provinsi');
    Route::get('/api/kota/{id}', [WilayahController::class, 'kota'])->name('api.kota');
    Route::get('/api/kecamatan/{id}', [WilayahController::class, 'kecamatan'])->name('api.kecamatan');
    Route::get('/api/kelurahan/{id}', [WilayahController::class, 'kelurahan'])->name('api.kelurahan');

    // -------------------------
    // Admin Antrian (butuh auth)
    // -------------------------
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin/poli/{kode}', [AdminController::class, 'dashboard']);
    Route::post('/admin/panggil', [AdminController::class, 'panggil']);
    Route::post('/admin/lewati/{antrian}', [AdminController::class, 'lewati']);
    Route::post('/admin/panggilLagi/{antrian}', [AdminController::class, 'panggilLagi']);

    // -------------------------
    // NFC Absensi (butuh auth)
    // -------------------------
    Route::get('/nfc', [NfcController::class, 'index'])->name('nfc.index');
    Route::post('/nfc/kartu', [NfcController::class, 'store'])->name('nfc.store');
    Route::delete('/nfc/kartu/{kartuNfc}', [NfcController::class, 'destroy'])->name('nfc.destroy');
    Route::get('/nfc/rekap', [NfcController::class, 'rekap'])->name('nfc.rekap');

});

// =====================
// MIDTRANS WEBHOOK (tanpa auth & tanpa CSRF)
// =====================
Route::post('/midtrans/webhook', [PosController::class, 'webhook'])->name('midtrans.webhook');