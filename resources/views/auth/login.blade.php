@extends('layouts.app')

@section('title', 'Masuk - Ahmad Handoko Law Office')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="p-4 text-center" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <div style="background: #c9a84c; border-radius: 12px; width:52px; height:52px; display:inline-flex; align-items:center; justify-content:center;" class="mb-3">
                        <i class="fas fa-balance-scale text-white fs-4"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-1">Ahmad Handoko Law Office</h4>
                    <p class="text-light opacity-75 small mb-0">Sistem Informasi Layanan & Konsultasi Hukum</p>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold text-dark mb-4 text-center">Masuk ke Akun Anda</h5>
                    
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label fw-semibold small text-secondary">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" id="username" class="form-control bg-light border-start-0 @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Masukkan username" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label fw-semibold small text-secondary mb-0">Password</label>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" placeholder="Masukkan password" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3 shadow-sm mb-3" style="background-color: #0f172a; border-color: #0f172a;">
                            <i class="fas fa-sign-in-alt me-2"></i> Masuk
                        </button>

                        <div class="text-center">
                            <span class="small text-muted">Belum memiliki akun klien?</span>
                            <a href="{{ route('register') }}" class="small fw-semibold text-decoration-none" style="color: #c9a84c;">Daftar Sekarang</a>
                        </div>
                    </form>

                    <hr class="my-4 text-muted">

                    <div class="bg-light p-3 rounded-3 small">
                        <strong class="text-dark d-block mb-1"><i class="fas fa-info-circle text-primary me-1"></i> Akun Demo Pengujian:</strong>
                        <div class="row g-1 text-muted" style="font-size: 11.5px;">
                            <div class="col-12">• <strong>Sekretaris (Admin):</strong> user: <code>admin</code> | pass: <code>password</code></div>
                            <div class="col-12">• <strong>Pengacara (Advokat):</strong> user: <code>pengacara1</code> | pass: <code>password</code></div>
                            <div class="col-12">• <strong>Klien (Pemohon):</strong> user: <code>klien1</code> | pass: <code>password</code></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-muted small text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

