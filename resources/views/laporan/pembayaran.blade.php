@extends('layouts.app')

@section('title', 'Laporan Keuangan & Pembayaran - Ahmad Handoko Law Office')
@section('page-title', 'Laporan Rekapitulasi Pembayaran & Kas Masuk')

@section('content')
<div class="card shadow-sm border-0 mb-4 d-print-none">
    <div class="card-body p-3">
        <form action="{{ route('admin.laporan.pembayaran') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="belum_bayar" {{ request('status') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="bulan" class="form-select">
                    <option value="">-- Semua Bulan --</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="tahun" class="form-control" placeholder="Tahun" value="{{ request('tahun', date('Y')) }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100" style="background-color: #0f172a; border-color: #0f172a;">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-2 text-end">
                <button type="button" onclick="window.print()" class="btn btn-gold w-100">
                    <i class="fas fa-print me-1"></i> Cetak PDF
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <!-- Header Kop Surat untuk Cetak -->
        <div class="text-center pb-3 mb-4 border-bottom border-dark">
            <h4 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 1px;">KANTOR ADVOKAT & KONSULTAN HUKUM</h4>
            <h3 class="fw-bold mb-1" style="color: #0f172a;">AHMAD HANDOKO LAW OFFICE</h3>
            <small class="text-muted d-block">Jl. Raden Intan No. 52, Enggal, Kota Bandar Lampung | Telp: (0721) 123-456</small>
            <h6 class="fw-bold mt-3 text-uppercase text-decoration-underline">LAPORAN REKAPITULASI PENERIMAAN BIAYA ADMINISTRASI & JASA HUKUM</h6>
        </div>

        <div class="row g-3 mb-4 d-print-none">
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                    <small class="text-muted d-block">Total Nominal Tagihan Diterbitkan</small>
                    <div class="fs-4 fw-bold text-dark">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-success bg-opacity-10 rounded-3 border border-success">
                    <small class="text-success fw-bold d-block">Total Kas Masuk Terverifikasi (Lunas)</small>
                    <div class="fs-4 fw-bold text-success">Rp {{ number_format($totalLunas, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr class="text-center small">
                        <th style="width: 5%;">No</th>
                        <th>No. Permohonan</th>
                        <th>Klien Pembayar</th>
                        <th>Keterangan Tagihan</th>
                        <th>Jumlah Tagihan</th>
                        <th>Status</th>
                        <th>Tanggal Transaksi</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse($data as $idx => $d)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="fw-bold">{{ $d->formulir->no_permohonan }}</td>
                        <td>{{ $d->formulir->klien->nama_lengkap }}</td>
                        <td>{{ $d->keterangan ?? '-' }}</td>
                        <td class="text-end fw-bold">Rp {{ number_format($d->jumlah_tagihan, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge {{ $d->status === 'lunas' ? 'bg-success' : ($d->status === 'menunggu_verifikasi' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ ucfirst(str_replace('_', ' ', $d->status)) }}
                            </span>
                        </td>
                        <td class="text-center">{{ $d->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Tidak ada data transaksi pembayaran untuk filter ini.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="fw-bold table-light">
                        <td colspan="4" class="text-end">TOTAL KAS LUNAS:</td>
                        <td class="text-end text-success">Rp {{ number_format($totalLunas, 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Tanda Tangan Cetak -->
        <div class="row mt-5 pt-4 d-none d-print-flex">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <small>Bandar Lampung, {{ now()->translatedFormat('d F Y') }}</small>
                <div class="fw-bold mt-1">Bagian Administrasi & Keuangan,</div>
                <div style="height: 70px;"></div>
                <div class="fw-bold text-decoration-underline">{{ auth()->user()->nama_lengkap }}</div>
                <small class="text-muted">Ahmad Handoko Law Office</small>
            </div>
        </div>
    </div>
</div>
@endsection

