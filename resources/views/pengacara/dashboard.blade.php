@extends('layouts.app')

@section('title', 'Dashboard Advokat - Ahmad Handoko Law Office')
@section('page-title', 'Dashboard Advokat / Pengacara')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #0f172a, #334155);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-num">{{ $totalDitangani }}</div>
                    <div class="stat-label">Total Perkara Ditugaskan</div>
                </div>
                <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #0284c7, #38bdf8);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-num">{{ $sedangBerjalan }}</div>
                    <div class="stat-label">Sedang Dalam Penanganan</div>
                </div>
                <div class="stat-icon"><i class="fas fa-spinner fa-spin"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #34d399);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-num">{{ $selesai }}</div>
                    <div class="stat-label">Perkara Selesai</div>
                </div>
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header py-3">
        <h6 class="mb-0 fw-bold"><i class="fas fa-gavel me-2 text-warning"></i> Daftar Perkara Hukum yang Saya Dampingi</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No. Permohonan</th>
                        <th>Nama Klien</th>
                        <th>Kategori Kasus</th>
                        <th>Jadwal Temu</th>
                        <th>Status Kasus</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perkaraList as $p)
                    <tr>
                        <td class="ps-3 fw-bold text-primary">{{ $p->no_permohonan }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $p->klien->nama_lengkap }}</div>
                            <small class="text-muted"><i class="fab fa-whatsapp text-success me-1"></i> {{ $p->klien->no_hp ?? '-' }}</small>
                        </td>
                        <td><span class="badge bg-secondary">{{ $p->kategori->nama_kategori }}</span></td>
                        <td>
                            @if($p->tgl_waktu_jadwal_temu)
                                <div class="small fw-semibold text-dark">{{ $p->tgl_waktu_jadwal_temu->format('d/m/Y H:i') }} WIB</div>
                                <small class="text-muted">{{ Str::limit($p->catatan_jadwal_temu, 25) }}</small>
                            @else
                                <span class="badge bg-light text-muted border">Belum Dijadwalkan</span>
                            @endif
                        </td>
                        <td>{!! $p->label_status_kasus !!}</td>
                        <td class="text-end pe-3">
                            <a href="{{ route('pengacara.detail', $p->id) }}" class="btn btn-primary btn-sm" style="background-color: #0f172a; border-color: #0f172a;">
                                <i class="fas fa-tasks me-1"></i> Tangani Perkara
                            </a>
                            <a href="{{ route('chat.room', $p->id) }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-comments"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Belum ada perkara yang ditugaskan kepada Anda.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

