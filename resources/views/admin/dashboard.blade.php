@extends('layouts.app')

@section('title', 'Dashboard Sekretaris - Ahmad Handoko Law Office')
@section('page-title', 'Dashboard Sekretaris / Administrator')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #0f172a, #334155);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-num">{{ $totalPerkara }}</div>
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
                    <div class="stat-num">{{ $perkaraPending }}</div>
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
                    <div class="stat-num">{{ $perkaraProses }}</div>
                    <div class="stat-label">Dalam Penanganan</div>
                </div>
                <div class="stat-icon"><i class="fas fa-gavel"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #34d399);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-num">Rp {{ number_format($totalPembayaran / 1000000, 1) }}M</div>
                    <div class="stat-label">Total Kas Masuk</div>
                </div>
                <div class="stat-icon"><i class="fas fa-coins"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="fas fa-clock me-2 text-warning"></i> 10 Permohonan Konsultasi Hukum Terbaru</h6>
        <a href="{{ route('admin.perkara') }}" class="btn btn-outline-light btn-sm">
            Lihat Semua Perkara <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No. Permohonan</th>
                        <th>Klien Pemohon</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Advokat Ditugaskan</th>
                        <th>Status Verifikasi</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perkaraList as $p)
                    <tr>
                        <td class="ps-3 fw-bold text-primary">{{ $p->no_permohonan }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $p->klien->nama_lengkap }}</div>
                            <small class="text-muted">{{ $p->klien->no_hp ?? '-' }}</small>
                        </td>
                        <td><span class="badge bg-secondary">{{ $p->kategori->nama_kategori }}</span></td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($p->pengacara)
                                <span class="badge bg-info text-dark"><i class="fas fa-user-tie me-1"></i> {{ $p->pengacara->nama_lengkap }}</span>
                            @else
                                <span class="badge bg-light text-danger border">Belum Ditugaskan</span>
                            @endif
                        </td>
                        <td>{!! $p->label_status_verifikasi !!}</td>
                        <td class="text-end pe-3">
                            <a href="{{ route('admin.perkara.detail', $p->id) }}" class="btn btn-primary btn-sm" style="background-color: #0f172a; border-color: #0f172a;">
                                <i class="fas fa-cog me-1"></i> Proses
                            </a>
                            <a href="{{ route('chat.room', $p->id) }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-comments"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada permohonan konsultasi masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

