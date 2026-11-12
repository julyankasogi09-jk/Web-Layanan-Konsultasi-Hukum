@extends('layouts.app')

@section('title', 'Penanganan Perkara - ' . $formulir->no_permohonan)
@section('page-title', 'Penanganan Perkara #' . $formulir->no_permohonan)

@section('content')
<div class="row g-4">
    <!-- Kolom Kiri: Uraian Perkara & Dokumen -->
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2 text-warning"></i> Data & Uraian Perkara</h6>
                <div>
                    {!! $formulir->label_status_kasus !!}
                </div>
            </div>
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Identitas Klien Pemohon:</h6>
                <div class="row g-2 mb-4 small">
                    <div class="col-sm-6"><strong>Nama Lengkap:</strong> {{ $formulir->klien->nama_lengkap }}</div>
                    <div class="col-sm-6"><strong>Kontak HP/WA:</strong> {{ $formulir->klien->no_hp ?? '-' }}</div>
                    <div class="col-sm-6"><strong>Email:</strong> {{ $formulir->klien->email ?? '-' }}</div>
                    <div class="col-sm-6"><strong>Pekerjaan:</strong> {{ $formulir->pekerjaan ?? '-' }}</div>
                    <div class="col-sm-12"><strong>Alamat Klien:</strong> {{ $formulir->klien->alamat ?? '-' }}</div>
                </div>

                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Kronologi Duduk Perkara:</h6>
                <div class="mb-2">
                    <strong>Bidang Hukum:</strong> <span class="badge bg-secondary">{{ $formulir->kategori->nama_kategori }}</span>
                </div>
                <div class="p-3 bg-light rounded-3 text-secondary mb-3" style="white-space: pre-line; line-height: 1.6;">
                    {{ $formulir->uraian_singkat_perkara }}
                </div>

                @if($formulir->bukti_awal)
                <div class="border-top pt-3 mb-3">
                    <strong>Berkas Bukti Awal Klien:</strong>
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $formulir->bukti_awal) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-file-download me-1"></i> Unduh / Periksa Bukti Awal Klien
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Chat Konsultasi Online -->
        <div class="card shadow-sm border-0">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-comments me-2 text-warning"></i> Konsultasi & Advis Hukum Daring</h6>
            </div>
            <div class="card-body p-3 text-center">
                <p class="small text-muted mb-3">Berikan advis hukum, arahan persiapan dokumen persidangan, atau koordinasi langsung kepada klien.</p>
                <a href="{{ route('chat.room', $formulir->id) }}" class="btn btn-primary" style="background-color: #0f172a; border-color: #0f172a;">
                    <i class="fas fa-comments me-2"></i> Buka Ruang Chat Konsultasi Perkara Ini
                </a>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Aksi Advokat (Update Progres, Upload Kontrak & Update Sidang) -->
    <div class="col-lg-5">
        <!-- 1. Update Status Progres Kasus -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-tasks me-2 text-warning"></i> 1. Update Progres Perkara</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('pengacara.progres', $formulir->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status Penanganan Kasus:</label>
                        <select name="status_kasus" class="form-select form-select-sm" required>
                            <option value="baru" {{ $formulir->status_kasus === 'baru' ? 'selected' : '' }}>Baru Diterima</option>
                            <option value="dalam_progres" {{ $formulir->status_kasus === 'dalam_progres' ? 'selected' : '' }}>Dalam Progres Penanganan</option>
                            <option value="selesai" {{ $formulir->status_kasus === 'selesai' ? 'selected' : '' }}>Kasus Selesai Ditangani</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100" style="background-color: #0f172a; border-color: #0f172a;">
                        <i class="fas fa-save me-1"></i> Perbarui Status Progres
                    </button>
                </form>
            </div>
        </div>

        <!-- 2. Unggah Berkas Kontrak & Surat Kuasa -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-file-contract me-2 text-warning"></i> 2. Berkas Kontrak Kesepakatan & Kuasa</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('pengacara.kontrak', $formulir->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Unggah File PDF Kontrak / Surat Kuasa:</label>
                        <input type="file" name="berkas_kontrak" class="form-control form-control-sm" required accept=".pdf">
                        <div class="form-text small">Hanya format PDF (Maksimal 5 MB).</div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Tahapan Sidang Awal:</label>
                        <input type="text" name="status_sidang" class="form-control form-control-sm" placeholder="Contoh: Pemberkasan Gugatan / Mediasi" value="{{ $formulir->kontrak->status_sidang ?? 'Pemberkasan' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Keterangan / Klausul Tambahan:</label>
                        <textarea name="keterangan" rows="2" class="form-control form-control-sm" placeholder="Catatan klausul hak dan kewajiban...">{{ $formulir->kontrak->keterangan ?? '' }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-sm btn-gold w-100">
                        <i class="fas fa-upload me-1"></i> Unggah Dokumen Kontrak
                    </button>
                </form>

                @if($formulir->kontrak)
                    <div class="border-top mt-3 pt-3">
                        <div class="small fw-bold text-success mb-2"><i class="fas fa-check-circle me-1"></i> Kontrak Aktif Tersimpan</div>
                        <a href="{{ asset('storage/' . $formulir->kontrak->berkas_kontrak) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-file-pdf me-1"></i> Buka Berkas Kontrak PDF
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- 3. Update Tahapan Sidang / Perkara -->
        @if($formulir->kontrak)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-balance-scale-right me-2 text-warning"></i> 3. Update Tahapan Sidang</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('pengacara.sidang', $formulir->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tahapan Persidangan Saat Ini:</label>
                        <input type="text" name="status_sidang" class="form-control form-control-sm" value="{{ $formulir->kontrak->status_sidang }}" placeholder="Contoh: Eksepsi, Pembuktian Saksi, Putusan Sela" required>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success w-100">
                        <i class="fas fa-sync-alt me-1"></i> Perbarui Tahapan Sidang
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

