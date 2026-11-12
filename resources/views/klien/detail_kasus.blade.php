@extends('layouts.app')

@section('title', 'Detail Permohonan - ' . $formulir->no_permohonan)
@section('page-title', 'Detail Kasus #' . $formulir->no_permohonan)

@section('content')
<div class="row g-4">
    <!-- Kolom Kiri: Informasi Kasus -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2 text-warning"></i> Informasi Permohonan</h6>
                <div>
                    {!! $formulir->label_status_verifikasi !!}
                    {!! $formulir->label_status_kasus !!}
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Nomor Registrasi Perkara</small>
                        <strong class="fs-6 text-dark">{{ $formulir->no_permohonan }}</strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Kategori Kasus</small>
                        <span class="badge bg-secondary fs-6">{{ $formulir->kategori->nama_kategori }}</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Tanggal Pengajuan</small>
                        <span>{{ $formulir->created_at->isoFormat('dddd, D MMMM YYYY - HH:mm') }} WIB</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Advokat / Pengacara Pembimbing</small>
                        @if($formulir->pengacara)
                            <strong class="text-primary"><i class="fas fa-user-tie me-1"></i> {{ $formulir->pengacara->nama_lengkap }}</strong>
                        @else
                            <span class="text-muted fst-italic">Menunggu penugasan oleh Sekretaris</span>
                        @endif
                    </div>
                </div>

                <div class="border-top pt-3 mb-4">
                    <h6 class="fw-bold text-dark mb-2">Uraian / Kronologi Duduk Perkara:</h6>
                    <div class="p-3 bg-light rounded-3 text-secondary" style="white-space: pre-line; line-height: 1.6;">
                        {{ $formulir->uraian_singkat_perkara }}
                    </div>
                </div>

                @if($formulir->bukti_awal)
                <div class="border-top pt-3 mb-4">
                    <h6 class="fw-bold text-dark mb-2">Bukti Pendukung Awal:</h6>
                    <a href="{{ route('berkas', ['folder' => explode('/', $formulir->bukti_awal)[0], 'filename' => basename($formulir->bukti_awal)]) }}"
                       target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-1"></i> Unduh / Buka Dokumen Bukti Awal
                    </a>
                </div>
                @endif

                @if($formulir->kontrak)
                <div class="border-top pt-3 mb-4">
                    <h6 class="fw-bold text-dark mb-2">Berkas Kontrak Kesepakatan & Surat Kuasa:</h6>
                    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 flex-wrap gap-2">
                        <div>
                            <strong>Tahapan Sidang:</strong> <span class="badge bg-primary">{{ $formulir->kontrak->status_sidang }}</span>
                            @if($formulir->kontrak->keterangan)
                                <div class="small text-muted mt-1">{{ $formulir->kontrak->keterangan }}</div>
                            @endif
                        </div>
                        <a href="{{ route('berkas', ['folder' => explode('/', $formulir->kontrak->berkas_kontrak)[0], 'filename' => basename($formulir->kontrak->berkas_kontrak)]) }}"
                           target="_blank" class="btn btn-sm" style="background:#c9a84c;color:#0f172a;font-weight:600;">
                            <i class="fas fa-file-pdf me-1"></i> Unduh Dokumen Kontrak (PDF)
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Jadwal Temu, Pembayaran & Chat -->
    <div class="col-lg-4">
        <!-- Jadwal Temu -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-calendar-check me-2 text-warning"></i> Jadwal Temu Konsultasi</h6>
            </div>
            <div class="card-body p-3">
                @if($formulir->status_jadwal_temu === 'dijadwalkan' && $formulir->tgl_waktu_jadwal_temu)
                    <div class="alert alert-success border-0 mb-2">
                        <div class="fw-bold fs-6"><i class="fas fa-clock me-1"></i> {{ $formulir->tgl_waktu_jadwal_temu->isoFormat('dddd, D MMMM YYYY') }}</div>
                        <div>Pukul: {{ $formulir->tgl_waktu_jadwal_temu->format('H:i') }} WIB</div>
                    </div>
                    @if($formulir->catatan_jadwal_temu)
                        <small class="text-muted d-block"><strong>Lokasi / Catatan:</strong> {{ $formulir->catatan_jadwal_temu }}</small>
                    @endif
                @else
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-calendar-times fs-2 mb-2 d-block opacity-50"></i>
                        <small>Jadwal temu konsultasi tatap muka / daring belum ditentukan oleh Sekretaris.</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tagihan & Pembayaran QRIS -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-file-invoice-dollar me-2 text-warning"></i> Biaya & Pembayaran</h6>
            </div>
            <div class="card-body p-3">
                @if($formulir->pembayaran)
                    <div class="mb-3">
                        <small class="text-muted d-block">Jumlah Tagihan Administrasi</small>
                        <div class="fs-4 fw-bold text-dark">Rp {{ number_format($formulir->pembayaran->jumlah_tagihan, 0, ',', '.') }}</div>
                        <div class="mt-1">{!! $formulir->pembayaran->label_status !!}</div>
                    </div>

                    @if($formulir->pembayaran->keterangan)
                        <div class="small text-muted bg-light p-2 rounded mb-3">
                            <strong>Keterangan:</strong> {{ $formulir->pembayaran->keterangan }}
                        </div>
                    @endif

                    @if($formulir->pembayaran->status === 'belum_bayar')

                        {{-- QRIS Section --}}
                        @if($qrisEnabled && $qrisString)
                        <div class="text-center mb-3 p-3 rounded-3" style="background: linear-gradient(135deg, #0f172a, #1e293b); box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                            <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                                <span class="badge bg-warning text-dark fw-bold" style="font-size:0.75rem;">
                                    <i class="fas fa-qrcode me-1"></i> QRIS DINAMIS
                                </span>
                                <span class="text-white-50 small">{{ $merchantName }}</span>
                            </div>

                            <div class="mb-2">
                                <small class="text-white-50 d-block" style="font-size:0.75rem;">Total Tagihan Pembayaran:</small>
                                <span class="badge px-3 py-1" style="background:#c9a84c;color:#0f172a;font-size:1.15rem;font-weight:700;">
                                    Rp {{ number_format($formulir->pembayaran->jumlah_tagihan, 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- QR Image Container -->
                            <div class="bg-white p-2 rounded-3 d-inline-block shadow-sm my-2" style="max-width: 250px;">
                                <img id="qris-image"
                                     src="{{ $qrImageUrl }}"
                                     alt="QRIS Pembayaran"
                                     class="img-fluid rounded"
                                     style="width: 220px; height: 220px; object-fit: contain; display: block;"
                                     onerror="handleQrError(this, '{{ urlencode($qrisString) }}')">
                            </div>

                            <p class="text-white-50 mb-2" style="font-size:0.75rem; line-height: 1.4;">
                                <i class="fas fa-mobile-alt me-1 text-warning"></i>
                                Buka <strong>DANA / GoPay / OVO / BCA / BRImo</strong> &rarr; Scan QR di atas.<br>
                                Nominal <strong>Rp {{ number_format($formulir->pembayaran->jumlah_tagihan, 0, ',', '.') }}</strong> akan terisi otomatis.
                            </p>

                            <!-- Tombol Aksi QR -->
                            <div class="d-flex justify-content-center gap-2 mb-1">
                                <button type="button" class="btn btn-sm btn-outline-light py-1 px-2" style="font-size:0.75rem;" onclick="copyQrisString()">
                                    <i class="fas fa-copy me-1"></i> Salin Kode QRIS
                                </button>
                                <a href="{{ $qrImageUrl }}" target="_blank" download="QRIS-{{ $formulir->no_permohonan }}.png" class="btn btn-sm btn-outline-warning py-1 px-2" style="font-size:0.75rem;">
                                    <i class="fas fa-download me-1"></i> Unduh QR
                                </a>
                            </div>

                            @if($qrisNote)
                                <div class="mt-2 pt-2 border-top border-secondary text-white-50" style="font-size:0.70rem;">
                                    {{ $qrisNote }}
                                </div>
                            @endif
                        </div>
                        <input type="hidden" id="raw-qris-data" value="{{ $qrisString }}">
                        @elseif(!$qrisEnabled || !$qrisString)
                            <div class="alert alert-info border-0 small mb-3">
                                <i class="fas fa-info-circle me-1"></i>
                                QRIS belum dikonfigurasi. Silakan hubungi sekretaris untuk info rekening tujuan pembayaran.
                            </div>
                        @endif

                        {{-- Upload Bukti Bayar --}}
                        <form action="{{ route('klien.upload_bayar', $formulir->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">
                                    <i class="fas fa-upload me-1 text-warning"></i>
                                    Upload Bukti Pembayaran / Screenshot:
                                </label>
                                <input type="file" name="bukti_bayar" class="form-control form-control-sm"
                                       accept="image/jpg,image/jpeg,image/png,application/pdf" required>
                                <small class="text-muted">Format: JPG, PNG, PDF. Maks 5MB.</small>
                            </div>
                            <button type="submit" class="btn btn-sm w-100 fw-semibold"
                                    style="background:#c9a84c;border:none;color:#0f172a;">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Bukti Pembayaran
                            </button>
                        </form>

                    @elseif($formulir->pembayaran->status === 'menunggu_verifikasi')
                        <div class="alert alert-warning border-0 small mb-0">
                            <i class="fas fa-hourglass-half me-1"></i> Bukti transfer telah terkirim. Menunggu verifikasi dari Sekretaris.
                        </div>
                        @if($formulir->pembayaran->bukti_bayar)
                            <div class="mt-2 text-center">
                                <a href="{{ route('berkas', ['folder' => explode('/', $formulir->pembayaran->bukti_bayar)[0], 'filename' => basename($formulir->pembayaran->bukti_bayar)]) }}"
                                   target="_blank" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-eye me-1"></i> Lihat Bukti yang Dikirim
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-success border-0 small mb-0">
                            <i class="fas fa-check-circle me-1"></i> Pembayaran telah diverifikasi <strong>LUNAS</strong>.
                        </div>
                    @endif
                @else
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-receipt fs-2 mb-2 d-block opacity-50"></i>
                        <small>Belum ada tagihan biaya administrasi untuk perkara ini.</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Chat Quick Action -->
        <div class="card shadow-sm border-0">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-comments me-2 text-warning"></i> Konsultasi Daring (Chat)</h6>
            </div>
            <div class="card-body p-3 text-center">
                <p class="small text-muted mb-3">Diskusikan perkembangan perkara Anda langsung dengan Advokat / Sekretaris secara online.</p>
                <a href="{{ route('chat.room', $formulir->id) }}" class="btn btn-primary w-100 py-2"
                   style="background-color: #0f172a; border-color: #0f172a;">
                    <i class="fas fa-paper-plane me-2"></i> Buka Ruang Chat Konsultasi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function handleQrError(img, encodedData) {
    // Step 1: Google Charts API fallback
    if (!img.dataset.step) {
        img.dataset.step = '1';
        img.src = 'https://chart.googleapis.com/chart?chs=280x280&cht=qr&chl=' + encodedData;
        return;
    }
    // Step 2: QuickChart API fallback
    if (img.dataset.step === '1') {
        img.dataset.step = '2';
        img.src = 'https://quickchart.io/qr?size=280&text=' + encodedData;
        return;
    }
    // Step 3: Friendly message if completely offline
    img.parentElement.innerHTML = '<div class="p-3 text-center text-dark"><i class="fas fa-exclamation-circle text-warning fs-3 mb-2 d-block"></i><small class="d-block fw-semibold">Gunakan Tombol "Salin Kode QRIS" di bawah untuk pembayaran</small></div>';
}

function copyQrisString() {
    const raw = document.getElementById('raw-qris-data').value;
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(raw).then(() => {
            alert('Kode QRIS Dinamis berhasil disalin!');
        });
    } else {
        const temp = document.createElement('textarea');
        temp.value = raw;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);
        alert('Kode QRIS Dinamis berhasil disalin!');
    }
}
</script>
@endpush
