<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KlienController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PengacaraController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaturanController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
})->name('home');

// Serve stored files directly (fix 403 Forbidden on storage link)
Route::get('/berkas/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path('app/public/' . $folder . '/' . $filename);
    if (!file_exists($path)) {
        abort(404, 'Berkas tidak ditemukan.');
    }
    $mime = mime_content_type($path) ?: 'application/octet-stream';
    return response()->file($path, ['Content-Type' => $mime]);
})->where('folder', '[A-Za-z0-9_-]+')->where('filename', '.+')->name('berkas');

// Legacy alias for /storage/... links
Route::get('/storage/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path('app/public/' . $folder . '/' . $filename);
    if (!file_exists($path)) {
        abort(404, 'Berkas tidak ditemukan.');
    }
    $mime = mime_content_type($path) ?: 'application/octet-stream';
    return response()->file($path, ['Content-Type' => $mime]);
})->where('folder', '[A-Za-z0-9_-]+')->where('filename', '.+');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Klien Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:klien'])->prefix('klien')->name('klien.')->group(function () {
    Route::get('/dashboard',           [KlienController::class, 'dashboard'])->name('dashboard');
    Route::get('/ajukan-konsultasi',   [KlienController::class, 'ajukanKonsultasi'])->name('ajukan');
    Route::post('/ajukan-konsultasi',  [KlienController::class, 'simpanKonsultasi'])->name('simpan');
    Route::get('/kasus/{id}',          [KlienController::class, 'detailKasus'])->name('detail');
    Route::post('/kasus/{id}/bayar',   [KlienController::class, 'uploadBuktiBayar'])->name('upload_bayar');
});

/*
|--------------------------------------------------------------------------
| Admin / Sekretaris Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:sekretaris'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',                      [AdminController::class,    'dashboard'])->name('dashboard');
    Route::get('/perkara',                        [AdminController::class,    'daftarPerkara'])->name('perkara');
    Route::get('/perkara/{id}',                   [AdminController::class,    'detailPerkara'])->name('perkara.detail');
    Route::post('/perkara/{id}/verifikasi',       [AdminController::class,    'verifikasiPerkara'])->name('perkara.verifikasi');
    Route::post('/perkara/{id}/assign',           [AdminController::class,    'assignPengacara'])->name('perkara.assign');
    Route::post('/perkara/{id}/jadwal',           [AdminController::class,    'jadwalkanTemu'])->name('perkara.jadwal');
    Route::post('/perkara/{id}/tagihan',          [AdminController::class,    'terbitkanTagihan'])->name('perkara.tagihan');
    Route::get('/pembayaran',                     [AdminController::class,    'kelolaPembayaran'])->name('pembayaran');
    Route::post('/pembayaran/{id}/konfirmasi',    [AdminController::class,    'konfirmasiPembayaran'])->name('pembayaran.konfirmasi');
    Route::get('/laporan/kasus',                  [LaporanController::class,  'kasus'])->name('laporan.kasus');
    Route::get('/laporan/pembayaran',             [LaporanController::class,  'pembayaran'])->name('laporan.pembayaran');
    // Pengaturan QRIS
    Route::get('/pengaturan/pembayaran',          [PengaturanController::class, 'pembayaran'])->name('pengaturan.pembayaran');
    Route::post('/pengaturan/pembayaran',         [PengaturanController::class, 'simpanPembayaran'])->name('pengaturan.pembayaran.simpan');
});

/*
|--------------------------------------------------------------------------
| Pengacara Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pengacara'])->prefix('pengacara')->name('pengacara.')->group(function () {
    Route::get('/dashboard',              [PengacaraController::class, 'dashboard'])->name('dashboard');
    Route::get('/perkara/{id}',           [PengacaraController::class, 'detailPerkara'])->name('detail');
    Route::post('/perkara/{id}/progres',  [PengacaraController::class, 'updateProgres'])->name('progres');
    Route::post('/perkara/{id}/kontrak',  [PengacaraController::class, 'uploadKontrak'])->name('kontrak');
    Route::post('/perkara/{id}/sidang',   [PengacaraController::class, 'updateStatusSidang'])->name('sidang');
});

/*
|--------------------------------------------------------------------------
| Chat Routes (Semua Role Terotentikasi)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('chat')->name('chat.')->group(function () {
    Route::get('/{formulirId}',         [ChatController::class, 'room'])->name('room');
    Route::post('/{formulirId}/kirim',  [ChatController::class, 'kirim'])->name('kirim');
    Route::get('/{formulirId}/fetch',   [ChatController::class, 'fetch'])->name('fetch');
});
