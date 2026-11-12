@extends('layouts.app')

@section('title', 'Kelola Perkara - Ahmad Handoko Law Office')
@section('page-title', 'Daftar Seluruh Permohonan Konsultasi & Perkara Hukum')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.perkara') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nomor permohonan atau nama klien..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Semua Status Verifikasi --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100" style="background-color: #0f172a; border-color: #0f172a;">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-2 text-end">
                <a href="{{ route('admin.perkara') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No. Permohonan</th>
                        <th>Klien Pemohon</th>
                        <th>Kategori Kasus</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Advokat Pembimbing</th>
                        <th>Status Verifikasi</th>
                        <th>Progres Perkara</th>
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
                        <td>{{ $p->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($p->pengacara)
                                <span class="badge bg-info text-dark"><i class="fas fa-user-tie me-1"></i> {{ $p->pengacara->nama_lengkap }}</span>
                            @else
                                <span class="badge bg-light text-danger border">Belum Ditugaskan</span>
                            @endif
                        </td>
                        <td>{!! $p->label_status_verifikasi !!}</td>
                        <td>{!! $p->label_status_kasus !!}</td>
                        <td class="text-end pe-3">
                            <a href="{{ route('admin.perkara.detail', $p->id) }}" class="btn btn-primary btn-sm" style="background-color: #0f172a; border-color: #0f172a;">
                                <i class="fas fa-cog me-1"></i> Kelola
                            </a>
                            <a href="{{ route('chat.room', $p->id) }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-comments"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">Tidak ada data perkara yang sesuai kriteria pencarian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($perkaraList->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $perkaraList->links() }}
    </div>
    @endif
</div>
@endsection

