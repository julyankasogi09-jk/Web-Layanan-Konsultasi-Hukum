@extends('layouts.app')

@section('title', 'Dashboard Klien - Ahmad Handoko Law Office')
@section('page-title', 'Dashboard Klien')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #0f172a, #334155);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-num">{{ $totalKasus }}</div>
                    <div class="stat-label">Total Permohonan</div>
                </div>
                <div class="stat-icon"><i class="fas fa-folder-open"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-num">{{ $menantiVerifikasi }}</div>
                    <div class="stat-label">Menunggu Verifikasi</div>
                </div>
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #0284c7, #38bdf8);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-num">{{ $kasusAktif }}</div>
                    <div class="stat-label">Dalam Progres</div>
                </div>
                <div class="stat-icon"><i class="fas fa-spinner fa-spin"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #34d399);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-num">{{ $kasusSelesai }}</div>
                    <div class="stat-label">Kasus Selesai</div>
                </div>
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i> Riwayat Permohonan Konsultasi Hukum Saya</h6>
        <a href="{{ route('klien.ajukan') }}" class="btn btn-gold btn-sm">
            <i class="fas fa-plus me-1"></i> Ajukan Konsultasi Baru
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No. Permohonan</th>
                        <th>Kategori Kasus</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status Verifikasi</th>
                        <th>Progres Kasus</th>
                        <th>Status Pembayaran</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($formulirList as $f)
                    <tr>
                        <td class="ps-3 fw-bold text-primary">{{ $f->no_permohonan }}</td>
                        <td><span class="badge bg-secondary">{{ $f->kategori->nama_kategori }}</span></td>
                        <td>{{ $f->created_at->format('d M Y') }}</td>
                        <td>{!! $f->label_status_verifikasi !!}</td>
                        <td>{!! $f->label_status_kasus !!}</td>
                        <td>
                            @if($f->pembayaran)
                                {!! $f->pembayaran->label_status !!}
                            @else
                                <span class="badge bg-light text-muted border">Belum Ditagihkan</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <a href="{{ route('klien.detail', $f->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                            <a href="{{ route('chat.room', $f->id) }}" class="btn btn-outline-success btn-sm position-relative">
                                <i class="fas fa-comments me-1"></i> Chat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fs-2 mb-2 d-block opacity-50"></i>
                            Belum ada permohonan konsultasi hukum yang diajukan.<br>
                            <a href="{{ route('klien.ajukan') }}" class="btn btn-gold btn-sm mt-2">
                                <i class="fas fa-plus me-1"></i> Ajukan Konsultasi Sekarang
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

