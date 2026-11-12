@extends('layouts.app')

@section('title', 'Kelola Perkara - ' . $formulir->no_permohonan)
@section('page-title', 'Manajemen Perkara #' . $formulir->no_permohonan)

@section('content')
<div class="row g-4">
    <!-- Kolom Kiri: Rincian Permohonan Klien -->
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2 text-warning"></i> Data Permohonan Konsultasi</h6>
                <div>
                    {!! $formulir->label_status_verifikasi !!}
                    {!! $formulir->label_status_kasus !!}
                </div>
            </div>
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">I. Identitas Klien Pemohon</h6>
                <div class="row g-2 mb-4 small">
                    <div class="col-sm-6"><strong>Nama Lengkap:</strong> {{ $formulir->klien->nama_lengkap }}</div>
                    <div class="col-sm-6"><strong>Username:</strong> {{ $formulir->klien->username }}</div>
                    <div class="col-sm-6"><strong>No. WhatsApp / HP:</strong> {{ $formulir->klien->no_hp ?? '-' }}</div>
                    <div class="col-sm-6"><strong>Email:</strong> {{ $formulir->klien->email ?? '-' }}</div>
                    <div class="col-sm-6"><strong>Tempat, Tgl Lahir:</strong> {{ $formulir->tempat_lahir ?? '-' }}, {{ $formulir->tgl_lahir ? $formulir->tgl_lahir->format('d/m/Y') : '-' }}</div>
                    <div class="col-sm-6"><strong>Jenis Kelamin / Agama:</strong> {{ $formulir->jenis_kelamin == 'L' ? 'Laki-laki' : ($formulir->jenis_kelamin == 'P' ? 'Perempuan' : '-') }} / {{ $formulir->agama ?? '-' }}</div>
                    <div class="col-sm-6"><strong>Pekerjaan:</strong> {{ $formulir->pekerjaan ?? '-' }}</div>
                    <div class="col-sm-12"><strong>Alamat Domisili:</strong> {{ $formulir->klien->alamat ?? '-' }}</div>
                </div>

                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">II. Kronologi Masalah Hukum</h6>
                <div class="mb-3">
                    <strong>Kategori:</strong> <span class="badge bg-secondary">{{ $formulir->kategori->nama_kategori }}</span>
                </div>
                <div class="p-3 bg-light rounded-3 text-secondary mb-3" style="white-space: pre-line; line-height: 1.6;">
                    {{ $formulir->uraian_singkat_perkara }}
                </div>

                @if($formulir->bukti_awal)
                <div class="border-top pt-3">
                    <strong>Bukti Awal Terunggah:</strong>
                    <div class="mt-2">
                        <a href="{{ route('berkas', ['folder' => explode('/', $formulir->bukti_awal)[0], 'filename' => basename($formulir->bukti_awal)]) }}"
                           target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-download me-1"></i> Buka Dokumen Bukti Awal Klien
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-comments me-2 text-warning"></i> Konsultasi Daring Langsung</h6>
            </div>
            <div class="card-body p-3 text-center">
                <p class="small text-muted mb-3">Komunikasi langsung dengan klien perihal dokumen kelengkapan atau advis hukum awal.</p>
                <a href="{{ route('chat.room', $formulir->id) }}" class="btn btn-primary" style="background-color: #0f172a; border-color: #0f172a;">
                    <i class="fas fa-comments me-2"></i> Buka Ruang Chat Konsultasi Perkara Ini
                </a>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Aksi Sekretaris (Verifikasi, Assign, Jadwal, Tagihan) -->
    <div class="col-lg-5">
        <!-- 1. Verifikasi Permohonan -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-check-double me-2 text-warning"></i> 1. Verifikasi Permohonan</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('admin.perkara.verifikasi', $formulir->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status Verifikasi Berkas:</label>
                        <select name="status_verifikasi" class="form-select form-select-sm" required>
                            <option value="pending" {{ $formulir->status_verifikasi === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                            <option value="disetujui" {{ $formulir->status_verifikasi === 'disetujui' ? 'selected' : '' }}>Setujui Permohonan</option>
                            <option value="ditolak" {{ $formulir->status_verifikasi === 'ditolak' ? 'selected' : '' }}>Tolak Permohonan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100" style="background-color: #0f172a; border-color: #0f172a;">
                        <i class="fas fa-save me-1"></i> Update Verifikasi
                    </button>
                </form>
            </div>
        </div>

        <!-- 2. Penugasan Advokat -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-user-tie me-2 text-warning"></i> 2. Penugasan Advokat Pembimbing</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('admin.perkara.assign', $formulir->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih Pengacara yang Menangani:</label>
                        <select name="pengacara_id" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Advokat --</option>
                            @foreach($pengacaraList as $p)
                                <option value="{{ $p->id }}" {{ $formulir->pengacara_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap }} ({{ $p->username }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-gold w-100">
                        <i class="fas fa-user-check me-1"></i> Tugaskan Advokat
                    </button>
                </form>
            </div>
        </div>

        <!-- 3. Jadwal Temu Resmi -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2 text-warning"></i> 3. Penjadwalan Temu Konsultasi</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('admin.perkara.jadwal', $formulir->id) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Tanggal & Jam Temu:</label>
                        <input type="datetime-local" name="tgl_waktu_jadwal_temu" class="form-control form-control-sm" value="{{ $formulir->tgl_waktu_jadwal_temu ? $formulir->tgl_waktu_jadwal_temu->format('Y-m-d\TH:i') : '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Lokasi / Ruang Temu / Tautan:</label>
                        <input type="text" name="catatan_jadwal_temu" class="form-control form-control-sm" placeholder="Contoh: Ruang Rapat Lt. 1 Kantor Ahmad Handoko" value="{{ $formulir->catatan_jadwal_temu }}">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100" style="background-color: #0f172a; border-color: #0f172a;">
                        <i class="fas fa-calendar-check me-1"></i> Simpan Jadwal Temu
                    </button>
                </form>
            </div>
        </div>

        <!-- 4. Terbitkan Tagihan Biaya Administrasi -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-receipt me-2 text-warning"></i> 4. Tagihan & Verifikasi Pembayaran</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('admin.perkara.tagihan', $formulir->id) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Jumlah Tagihan (Rp):</label>
                        <input type="number" name="jumlah_tagihan" class="form-control form-control-sm" value="{{ $formulir->pembayaran->jumlah_tagihan ?? 0 }}" required min="0">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Keterangan Biaya:</label>
                        <textarea name="keterangan" rows="2" class="form-control form-control-sm" placeholder="Uraian biaya administrasi & konsultasi...">{{ $formulir->pembayaran->keterangan ?? '' }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-sm btn-gold w-100">
                        <i class="fas fa-file-invoice-dollar me-1"></i> Terbitkan / Perbarui Tagihan
                    </button>
                </form>

                @if($formulir->pembayaran)
                    <div class="border-top pt-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-bold">Status Bayar:</span>
                            {!! $formulir->pembayaran->label_status !!}
                        </div>
                        @if($formulir->pembayaran->bukti_bayar)
                            <div class="mb-2">
                                @php
                                    $buktiParts = explode('/', $formulir->pembayaran->bukti_bayar);
                                    $buktiFolder = $buktiParts[0] ?? 'bukti_bayar';
                                    $buktiFile   = basename($formulir->pembayaran->bukti_bayar);
                                    $buktiExt    = strtolower(pathinfo($buktiFile, PATHINFO_EXTENSION));
                                @endphp
                                <a href="{{ route('berkas', ['folder' => $buktiFolder, 'filename' => $buktiFile]) }}"
                                   target="_blank" class="btn btn-sm btn-outline-info w-100">
                                    <i class="fas fa-eye me-1"></i> Lihat Bukti Transfer Klien
                                </a>
                                {{-- Preview jika image --}}
                                @if(in_array($buktiExt, ['jpg','jpeg','png']))
                                    <div class="mt-2 text-center">
                                        <img src="{{ route('berkas', ['folder' => $buktiFolder, 'filename' => $buktiFile]) }}"
                                             class="img-fluid rounded shadow-sm" style="max-height:200px;"
                                             alt="Bukti Bayar">
                                    </div>
                                @endif
                            </div>
                            @if($formulir->pembayaran->status !== 'lunas')
                            <form action="{{ route('admin.pembayaran.konfirmasi', $formulir->pembayaran->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success w-100">
                                    <i class="fas fa-check-circle me-1"></i> Konfirmasi Pembayaran LUNAS
                                </button>
                            </form>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

