@extends('layouts.app')

@section('title', 'Registrasi Klien - Ahmad Handoko Law Office')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="p-4 text-center" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <div style="background: #c9a84c; border-radius: 12px; width:50px; height:50px; display:inline-flex; align-items:center; justify-content:center;" class="mb-2">
                        <i class="fas fa-user-plus text-white fs-4"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-1">Registrasi Akun Klien</h4>
                    <p class="text-light opacity-75 small mb-0">Daftar untuk mengajukan konsultasi hukum online</p>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label fw-semibold small text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control bg-light @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" placeholder="Sesuai KTP" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label fw-semibold small text-secondary">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="username" class="form-control bg-light @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Tanpa spasi" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold small text-secondary">Email</label>
                                <input type="email" name="email" id="email" class="form-control bg-light @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@email.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="no_hp" class="form-label fw-semibold small text-secondary">No. WhatsApp / HP</label>
                                <input type="text" name="no_hp" id="no_hp" class="form-control bg-light @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold small text-secondary">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control bg-light @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold small text-secondary">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light" placeholder="Ulangi password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3 shadow-sm mb-3" style="background-color: #0f172a; border-color: #0f172a;">
                            <i class="fas fa-check-circle me-2"></i> Buat Akun Klien
                        </button>

                        <div class="text-center">
                            <span class="small text-muted">Sudah punya akun?</span>
                            <a href="{{ route('login') }}" class="small fw-semibold text-decoration-none" style="color: #c9a84c;">Masuk di Sini</a>
                        </div>
                    </form>
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

