<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ahmad Handoko Law Office')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --navy: #0f172a;
            --navy-light: #1e293b;
            --gold: #c9a84c;
            --gold-light: #f1d98a;
            --sidebar-width: 260px;
        }
        body { background-color: #f1f5f9; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            position: fixed; top: 0; left: 0; height: 100vh;
            width: var(--sidebar-width); background: var(--navy);
            z-index: 1000; overflow-y: auto; transition: 0.3s;
        }
        .sidebar-brand {
            padding: 20px 16px; border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand h5 { color: var(--gold); font-weight: 700; font-size: 14px; margin: 0; }
        .sidebar-brand small { color: rgba(255,255,255,0.5); font-size: 11px; }
        .sidebar-nav { padding: 10px 0; }
        .sidebar-nav .nav-section {
            padding: 10px 16px 4px; font-size: 10px;
            color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px;
        }
        .sidebar-nav a {
            display: flex; align-items: center; padding: 10px 20px;
            color: rgba(255,255,255,0.75); text-decoration: none; font-size: 13.5px;
            transition: 0.2s; border-left: 3px solid transparent;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: rgba(255,255,255,0.08); color: #fff;
            border-left-color: var(--gold);
        }
        .sidebar-nav a i { width: 20px; margin-right: 10px; font-size: 14px; }
        .main-content { margin-left: var(--sidebar-width); padding: 0; }
        .topbar {
            background: #fff; border-bottom: 1px solid #e2e8f0;
            padding: 12px 24px; display: flex; align-items: center;
            justify-content: space-between; position: sticky; top: 0; z-index: 100;
        }
        .topbar h6 { margin: 0; font-weight: 600; color: var(--navy); font-size: 15px; }
        .content-area { padding: 24px; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-radius: 8px; }
        .card-header { background: var(--navy); color: #fff; border-radius: 8px 8px 0 0 !important; }
        .btn-gold { background: var(--gold); color: #fff; border: none; }
        .btn-gold:hover { background: #b8963e; color: #fff; }
        .stat-card { border-radius: 10px; padding: 20px; color: #fff; }
        .stat-card .stat-icon { font-size: 32px; opacity: 0.8; }
        .stat-card .stat-num { font-size: 28px; font-weight: 700; }
        .stat-card .stat-label { font-size: 13px; opacity: 0.9; }
        .badge { font-size: 11px; }
        .table th { font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; }
        @media (max-width: 768px) {
            .sidebar { width: 0; overflow: hidden; }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="d-flex align-items-center gap-2">
                <div style="background: var(--gold); border-radius: 6px; width:36px; height:36px; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-balance-scale text-white" style="font-size:16px;"></i>
                </div>
                <div>
                    <h5>Ahmad Handoko</h5>
                    <small>Law Office</small>
                </div>
            </div>
            <div class="mt-2 p-2" style="background:rgba(255,255,255,0.05); border-radius:6px;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:30px;height:30px;background:var(--gold);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:var(--navy);">
                        {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}
                    </div>
                    <div>
                        <div style="color:#fff;font-size:12px;font-weight:600;">{{ Str::limit(auth()->user()->nama_lengkap, 20) }}</div>
                        <div style="color:var(--gold);font-size:10px;">{{ ucfirst(auth()->user()->role->nama_role) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            @if(auth()->user()->isKlien())
                <span class="nav-section">Menu Klien</span>
                <a href="{{ route('klien.dashboard') }}" class="{{ request()->routeIs('klien.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('klien.ajukan') }}" class="{{ request()->routeIs('klien.ajukan') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Ajukan Konsultasi
                </a>

            @elseif(auth()->user()->isAdmin())
                <span class="nav-section">Menu Sekretaris</span>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('admin.perkara') }}" class="{{ request()->routeIs('admin.perkara*') ? 'active' : '' }}">
                    <i class="fas fa-folder-open"></i> Kelola Perkara
                </a>
                <a href="{{ route('admin.pembayaran') }}" class="{{ request()->routeIs('admin.pembayaran') ? 'active' : '' }}">
                    <i class="fas fa-money-check-alt"></i> Pembayaran
                </a>
                <span class="nav-section">Laporan</span>
                <a href="{{ route('admin.laporan.kasus') }}">
                    <i class="fas fa-chart-bar"></i> Laporan Perkara
                </a>
                <a href="{{ route('admin.laporan.pembayaran') }}">
                    <i class="fas fa-file-invoice-dollar"></i> Laporan Keuangan
                </a>
                <span class="nav-section">Pengaturan</span>
                <a href="{{ route('admin.pengaturan.pembayaran') }}" class="{{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
                    <i class="fas fa-qrcode"></i> Pengaturan QRIS
                </a>

            @elseif(auth()->user()->isPengacara())
                <span class="nav-section">Menu Pengacara</span>
                <a href="{{ route('pengacara.dashboard') }}" class="{{ request()->routeIs('pengacara.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            @endif

            <span class="nav-section">Akun</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link w-100 text-start p-0" style="text-decoration:none;">
                    <a href="#" onclick="this.closest('form').submit()" style="color:rgba(255,255,255,0.75);">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </a>
                </button>
            </form>
        </nav>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h6><i class="fas fa-angle-right me-1 text-muted"></i> @yield('page-title', 'Halaman')</h6>
            <div class="text-muted" style="font-size:12px;">
                <i class="fas fa-clock me-1"></i> {{ now()->isoFormat('dddd, D MMMM YYYY') }}
            </div>
        </div>
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
    @endauth

    @guest
        @yield('content')
    @endguest

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

