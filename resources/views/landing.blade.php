<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahmad Handoko Law Office - Bandar Lampung</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --navy: #0f172a; --gold: #c9a84c; }
        body { font-family: 'Segoe UI', sans-serif; }
        .navbar { background: var(--navy); }
        .navbar-brand { color: var(--gold) !important; font-weight: 700; }
        .navbar-nav .nav-link { color: rgba(255,255,255,0.8) !important; }
        .navbar-nav .nav-link:hover { color: var(--gold) !important; }
        .hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0f172a 100%);
            padding: 100px 0 80px; color: #fff;
        }
        .hero h1 { font-size: 42px; font-weight: 800; line-height: 1.2; }
        .hero h1 span { color: var(--gold); }
        .hero p { font-size: 17px; color: rgba(255,255,255,0.75); }
        .btn-gold { background: var(--gold); color: #fff; border: none; padding: 12px 28px; font-weight: 600; border-radius: 6px; }
        .btn-gold:hover { background: #b8963e; color: #fff; }
        .btn-outline-gold { border: 2px solid var(--gold); color: var(--gold); padding: 12px 28px; font-weight: 600; border-radius: 6px; }
        .btn-outline-gold:hover { background: var(--gold); color: #fff; }
        .section-title { color: var(--navy); font-weight: 700; }
        .section-title span { color: var(--gold); }
        .service-card { border: none; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); transition: 0.3s; }
        .service-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .service-icon { width: 60px; height: 60px; background: linear-gradient(135deg, var(--navy), #334155); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
        .service-icon i { color: var(--gold); font-size: 24px; }
        .team-card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden; }
        .team-avatar { width: 80px; height: 80px; border-radius: 50%; background: var(--navy); display: flex; align-items: center; justify-content: center; margin: 20px auto 0; color: var(--gold); font-size: 28px; font-weight: 700; }
        .footer { background: var(--navy); color: rgba(255,255,255,0.75); }
        .footer h6 { color: var(--gold); font-weight: 700; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-balance-scale me-2"></i>Ahmad Handoko Law Office
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav ms-auto me-3">
                    <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tim">Tim Advokat</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                </ul>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-gold me-2">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-gold">Daftar Sekarang</a>
                @else
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-gold">Dashboard</a>
                    @elseif(auth()->user()->isPengacara())
                        <a href="{{ route('pengacara.dashboard') }}" class="btn btn-gold">Dashboard</a>
                    @else
                        <a href="{{ route('klien.dashboard') }}" class="btn btn-gold">Dashboard Saya</a>
                    @endif
                @endguest
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <p class="text-uppercase" style="color: var(--gold); font-size: 13px; letter-spacing: 2px; font-weight: 600;">
                        <i class="fas fa-gavel me-2"></i>Kantor Hukum Terpercaya
                    </p>
                    <h1>Solusi Hukum Profesional<br>untuk <span>Setiap Perkara</span></h1>
                    <p class="mt-3">Ahmad Handoko Law Office hadir memberikan layanan konsultasi dan pendampingan hukum terbaik di Bandar Lampung. Kami menangani perkara pidana, perdata, korporasi, ketenagakerjaan, dan tata usaha negara.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="{{ route('register') }}" class="btn btn-gold"><i class="fas fa-file-alt me-2"></i>Ajukan Konsultasi</a>
                        <a href="#layanan" class="btn btn-outline-gold"><i class="fas fa-info-circle me-2"></i>Lihat Layanan</a>
                    </div>
                </div>
                <div class="col-lg-5 text-center d-none d-lg-block">
                    <i class="fas fa-balance-scale" style="font-size: 180px; color: rgba(201,168,76,0.2);"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="py-4" style="background: var(--gold);">
        <div class="container">
            <div class="row text-center text-white">
                <div class="col-md-3 col-6 py-2 border-end border-white border-opacity-25">
                    <div class="fs-2 fw-bold">500+</div><small>Kasus Ditangani</small>
                </div>
                <div class="col-md-3 col-6 py-2 border-end border-white border-opacity-25">
                    <div class="fs-2 fw-bold">15+</div><small>Tahun Pengalaman</small>
                </div>
                <div class="col-md-3 col-6 py-2 border-end border-white border-opacity-25">
                    <div class="fs-2 fw-bold">98%</div><small>Kepuasan Klien</small>
                </div>
                <div class="col-md-3 col-6 py-2">
                    <div class="fs-2 fw-bold">5</div><small>Bidang Hukum</small>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan -->
    <section class="py-5" id="layanan">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Bidang <span>Layanan Hukum</span></h2>
                <p class="text-muted">Kami ahli dalam menangani berbagai jenis perkara hukum secara profesional</p>
            </div>
            <div class="row g-4">
                @foreach([
                    ['icon'=>'fa-gavel',          'judul'=>'Hukum Pidana',              'desc'=>'Pendampingan hukum kasus tindak pidana, pembelaan tersangka dan terdakwa, serta upaya hukum banding dan kasasi.'],
                    ['icon'=>'fa-handshake',       'judul'=>'Hukum Perdata',             'desc'=>'Sengketa kontrak, warisan, perceraian, harta bersama, dan sengketa kepemilikan aset atau tanah.'],
                    ['icon'=>'fa-landmark',        'judul'=>'Tata Usaha Negara',         'desc'=>'Gugatan terhadap keputusan administrasi negara, sengketa perizinan usaha, dan sertifikasi lahan.'],
                    ['icon'=>'fa-building',        'judul'=>'Hukum Korporasi & Perbankan','desc'=>'Hukum perusahaan, restrukturisasi utang, sengketa investasi, dan kepatuhan regulasi perbankan.'],
                    ['icon'=>'fa-users',           'judul'=>'Ketenagakerjaan',           'desc'=>'Perselisihan PHK, pesangon, hubungan industrial, dan sengketa antara karyawan dengan perusahaan.'],
                    ['icon'=>'fa-comments',        'judul'=>'Konsultasi Online',         'desc'=>'Layanan konsultasi hukum secara daring melalui platform digital kami yang aman dan terenkripsi.'],
                ] as $layanan)
                <div class="col-md-4">
                    <div class="service-card card p-4 h-100">
                        <div class="service-icon"><i class="fas {{ $layanan['icon'] }}"></i></div>
                        <h5 class="fw-bold" style="color: var(--navy);">{{ $layanan['judul'] }}</h5>
                        <p class="text-muted small">{{ $layanan['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Tim Advokat -->
    <section class="py-5 bg-light" id="tim">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Tim <span>Advokat Kami</span></h2>
                <p class="text-muted">Didukung oleh para profesional hukum berpengalaman dan berdedikasi</p>
            </div>
            <div class="row g-4 justify-content-center">
                @foreach([
                    ['inisial'=>'AH', 'nama'=>'Ahmad Handoko, S.H.', 'jabatan'=>'Senior Partner & Founder', 'bidang'=>'Hukum Perbankan & Korporasi'],
                    ['inisial'=>'YH', 'nama'=>'Yopi Hendro, S.H., M.H.', 'jabatan'=>'Associate Partner', 'bidang'=>'Hukum Pidana & Perdata'],
                ] as $tim)
                <div class="col-md-4 col-sm-6">
                    <div class="team-card card text-center p-4">
                        <div class="team-avatar">{{ $tim['inisial'] }}</div>
                        <div class="p-3">
                            <h6 class="fw-bold mb-1" style="color: var(--navy);">{{ $tim['nama'] }}</h6>
                            <small class="text-muted d-block">{{ $tim['jabatan'] }}</small>
                            <span class="badge mt-2" style="background: var(--navy); font-size: 11px;">{{ $tim['bidang'] }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-5" style="background: var(--navy);">
        <div class="container text-center text-white">
            <h3 class="fw-bold mb-2">Butuh Bantuan Hukum?</h3>
            <p style="color: rgba(255,255,255,0.7);">Daftarkan diri Anda dan ajukan konsultasi hukum secara online sekarang juga.</p>
            <a href="{{ route('register') }}" class="btn btn-gold btn-lg mt-2">
                <i class="fas fa-file-alt me-2"></i>Ajukan Konsultasi Sekarang
            </a>
        </div>
    </section>

    <!-- Kontak -->
    <section class="py-5" id="kontak">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4 d-flex gap-3">
                    <div style="color: var(--gold); font-size: 24px;"><i class="fas fa-map-marker-alt"></i></div>
                    <div><strong>Alamat</strong><br><small class="text-muted">Jl. Raden Intan No. 52, Enggal, Bandar Lampung 35117</small></div>
                </div>
                <div class="col-md-4 d-flex gap-3">
                    <div style="color: var(--gold); font-size: 24px;"><i class="fas fa-phone-alt"></i></div>
                    <div><strong>Telepon</strong><br><small class="text-muted">(0721) 123-456 | 085211110001</small></div>
                </div>
                <div class="col-md-4 d-flex gap-3">
                    <div style="color: var(--gold); font-size: 24px;"><i class="fas fa-envelope"></i></div>
                    <div><strong>Email</strong><br><small class="text-muted">info@ahmadhandokolawoffice.web.id</small></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-4">
        <div class="container text-center">
            <small>&copy; {{ date('Y') }} Ahmad Handoko Law Office Bandar Lampung. All rights reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

