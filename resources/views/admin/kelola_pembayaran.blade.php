@extends('layouts.app')

@section('title', 'Kelola Pembayaran - Ahmad Handoko Law Office')
@section('page-title', 'Daftar Transaksi Pembayaran & Verifikasi Transfer')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header py-3">
        <h6 class="mb-0 fw-bold"><i class="fas fa-money-check-alt me-2 text-warning"></i> Data Transaksi Tagihan & Pembayaran Layanan Hukum</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">ID Bayar</th>
                        <th>No. Permohonan</th>
                        <th>Klien Pemohon</th>
                        <th>Jumlah Tagihan</th>
                        <th>Bukti Transfer</th>
                        <th>Status</th>
                        <th>Waktu Tagihan</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaranList as $p)
                    <tr>
                        <td class="ps-3 fw-bold">#{{ $p->id }}</td>
                        <td>
                            <a href="{{ route('admin.perkara.detail', $p->formulir->id) }}" class="fw-semibold text-decoration-none">
                                {{ $p->formulir->no_permohonan }}
                            </a>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $p->formulir->klien->nama_lengkap }}</div>
                            <small class="text-muted">{{ $p->formulir->klien->no_hp ?? '-' }}</small>
                        </td>
                        <td class="fw-bold text-dark">Rp {{ number_format($p->jumlah_tagihan, 0, ',', '.') }}</td>
                        <td>
                            @if($p->bukti_bayar)
                                <a href="{{ route('berkas', ['folder' => explode('/', $p->bukti_bayar)[0], 'filename' => basename($p->bukti_bayar)]) }}"
                                   target="_blank" class="btn btn-outline-primary btn-sm py-0">
                                    <i class="fas fa-file-image me-1"></i> Lihat Bukti
                                </a>
                            @else
                                <span class="badge bg-light text-muted border">Belum Unggah</span>
                            @endif
                        </td>
                        <td>{!! $p->label_status !!}</td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end pe-3">
                            @if($p->status === 'menunggu_verifikasi')
                                <form action="{{ route('admin.pembayaran.konfirmasi', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check me-1"></i> Konfirmasi Lunas
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">Belum ada transaksi pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pembayaranList->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $pembayaranList->links() }}
    </div>
    @endif
</div>
@endsection

