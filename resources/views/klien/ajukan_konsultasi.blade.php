@extends('layouts.app')

@section('title', 'Ajukan Konsultasi - Ahmad Handoko Law Office')
@section('page-title', 'Formulir Permohonan Konsultasi Hukum')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-header py-3" style="background: #0f172a;">
                <h6 class="mb-0 fw-bold text-white"><i class="fas fa-file-signature me-2" style="color: #c9a84c;"></i> Pengisian Formulir Permohonan Layanan Hukum</h6>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info border-0 rounded-3 mb-4 small">
                    <i class="fas fa-info-circle me-2"></i> Mohon lengkapi formulir permohonan konsultasi hukum berikut dengan sebenar-benarnya. Data Anda akan dijaga kerahasiaannya oleh Kantor Hukum Ahmad Handoko sesuai kode etik advokat.
                </div>

                <form action="{{ route('klien.simpan') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-user-circle me-2 text-primary"></i> I. Data Identitas Pemohon</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Lengkap Pemohon</label>
                            <input type="text" class="form-control bg-light" value="{{ auth()->user()->nama_lengkap }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">No. WhatsApp / HP</label>
                            <input type="text" class="form-control bg-light" value="{{ auth()->user()->no_hp ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir') }}" placeholder="Kota kelahiran">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control @error('tgl_lahir') is-invalid @enderror" value="{{ old('tgl_lahir') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Agama</label>
                            <input type="text" name="agama" class="form-control @error('agama') is-invalid @enderror" value="{{ old('agama') }}" placeholder="Islam, Kristen, dll.">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Pekerjaan / Profesi</label>
                            <input type="text" name="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror" value="{{ old('pekerjaan') }}" placeholder="Karyawan, Wiraswasta, dll.">
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-balance-scale me-2 text-primary"></i> II. Pokok Perkara Hukum</h6>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kategori Kasus / Bidang Hukum <span class="text-danger">*</span></label>
                        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori Kasus --</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Uraian Singkat / Kronologi Perkara <span class="text-danger">*</span></label>
                        <textarea name="uraian_singkat_perkara" rows="5" class="form-control @error('uraian_singkat_perkara') is-invalid @enderror" placeholder="Jelaskan secara kronologis duduk perkara masalah hukum yang dihadapi (minimal 20 karakter)..." required>{{ old('uraian_singkat_perkara') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Unggah Bukti Awal / Dokumen Pendukung (Opsional)</label>
                        <input type="file" name="bukti_awal" class="form-control @error('bukti_awal') is-invalid @enderror">
                        <div class="form-text small">Format file yang diperbolehkan: PDF, JPG, PNG (Maksimal 2 MB).</div>
                    </div>

                    <div class="d-flex justify-content-between pt-3 border-top">
                        <a href="{{ route('klien.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-gold px-4">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Permohonan Konsultasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

