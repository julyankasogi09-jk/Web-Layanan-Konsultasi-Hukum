@extends('layouts.app')

@section('title', 'Pengaturan QRIS')

@section('content')
<div class="container-fluid px-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color:#c9a84c;">
                <i class="fas fa-qrcode me-2"></i>Pengaturan Pembayaran QRIS
            </h4>
            <p class="text-muted mb-0 small">Konfigurasi QRIS untuk pembayaran klien via buatqris.site</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Form Pengaturan --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3" style="background:linear-gradient(135deg,#0f172a,#1e293b);border-bottom:2px solid #c9a84c;">
                    <h6 class="mb-0 text-white fw-semibold"><i class="fas fa-cog me-2 text-warning"></i>Konfigurasi QRIS</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.pengaturan.pembayaran.simpan') }}" method="POST">
                        @csrf

                        {{-- Toggle Aktifkan QRIS --}}
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="qris_enabled" name="qris_enabled"
                                       {{ ($settings['qris_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                       style="width:3em;height:1.5em;">
                                <label class="form-check-label fw-semibold ms-2" for="qris_enabled">
                                    Aktifkan Pembayaran QRIS
                                </label>
                            </div>
                            <small class="text-muted">Jika dinonaktifkan, klien tidak akan melihat QR code pada halaman pembayaran.</small>
                        </div>

                        {{-- Nama Merchant --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nama Merchant <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('qris_merchant_name') is-invalid @enderror"
                                   name="qris_merchant_name"
                                   value="{{ old('qris_merchant_name', $settings['qris_merchant_name'] ?? 'Ahmad Handoko Law Office') }}"
                                   placeholder="Nama yang tampil pada QRIS">
                            @error('qris_merchant_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- String QRIS --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                String QRIS Statis
                                <span class="badge bg-info text-dark ms-1" style="font-size:0.7em;">Dari DANA Bisnis / GoPay / OVO / BRI / BCA</span>
                            </label>
                            <textarea class="form-control font-monospace @error('qris_string') is-invalid @enderror"
                                      name="qris_string" rows="6"
                                      placeholder="Paste string QRIS statis Anda di sini. Contoh: 00020101021226...">{{ old('qris_string', $settings['qris_string'] ?? '') }}</textarea>
                            @error('qris_string')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="mt-2 p-3 rounded" style="background:#f8f9fa;border-left:4px solid #c9a84c;">
                                <p class="mb-1 small fw-semibold text-dark"><i class="fas fa-info-circle me-1 text-warning"></i>Cara mendapatkan String QRIS:</p>
                                <ol class="mb-0 small text-muted ps-3">
                                    <li>Buka aplikasi DANA / GoPay / OVO akun bisnis Anda</li>
                                    <li>Pergi ke menu <strong>Terima Pembayaran / QR Code</strong></li>
                                    <li>Simpan gambar QR Code lalu scan dengan aplikasi decoder QR</li>
                                    <li>Atau hubungi penyedia e-wallet untuk mendapat raw string QRIS</li>
                                </ol>
                            </div>
                        </div>

                        {{-- Catatan Pembayaran --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Catatan Pembayaran (Opsional)</label>
                            <textarea class="form-control" name="qris_note" rows="3"
                                      placeholder="Pesan yang tampil di bawah QR code...">{{ old('qris_note', $settings['qris_note'] ?? '') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-warning fw-bold px-4" style="background:#c9a84c;border:none;color:#0f172a;">
                            <i class="fas fa-save me-2"></i>Simpan Pengaturan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Preview QRIS --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3" style="background:linear-gradient(135deg,#0f172a,#1e293b);border-bottom:2px solid #c9a84c;">
                    <h6 class="mb-0 text-white fw-semibold"><i class="fas fa-eye me-2 text-warning"></i>Preview QR Code</h6>
                </div>
                <div class="card-body p-4 text-center">
                    @php
                        $qrisStr = $settings['qris_string'] ?? '';
                        $qrisOn  = ($settings['qris_enabled'] ?? '0') === '1';
                        $previewUrl = '';
                        if ($qrisStr) {
                            $previewDynamic = \App\Models\PaymentSetting::makeDynamicQris($qrisStr, 50000);
                            $previewUrl = \App\Models\PaymentSetting::getQrImageUrl($previewDynamic);
                        }
                    @endphp

                    @if($qrisStr && $qrisOn)
                        <div class="p-3 rounded-3 d-inline-block mb-3" style="background:#fff;box-shadow:0 0 0 8px #0f172a;">
                            <img src="{{ $previewUrl }}"
                                 alt="Preview QRIS"
                                 style="width:220px;height:220px;object-fit:contain;"
                                 onerror="if(!this.dataset.retry){this.dataset.retry=1;this.src='https://chart.googleapis.com/chart?chs=220x220&cht=qr&chl={{ urlencode($previewDynamic ?? '') }}';}else{this.parentElement.innerHTML='<div style=\'width:220px;height:220px;display:flex;align-items:center;justify-content:center;\'><i class=\'fas fa-qrcode\' style=\'font-size:5rem;color:#dee2e6;\'></i></div>';}">
                        </div>
                        <p class="small text-muted mb-0">Preview nominal dinamis: <strong>Rp 50.000</strong></p>
                        <p class="small text-success fw-semibold mt-1"><i class="fas fa-check-circle me-1"></i>QRIS Aktif & Siap Digunakan</p>
                    @elseif($qrisStr && !$qrisOn)
                        <div class="p-5 text-muted">
                            <i class="fas fa-toggle-off" style="font-size:4rem;color:#dee2e6;"></i>
                            <p class="mt-3 mb-0">QRIS tersimpan tapi <strong>dinonaktifkan</strong>.<br>Aktifkan toggle di kiri untuk menampilkan QR kepada klien.</p>
                        </div>
                    @else
                        <div class="p-5 text-muted">
                            <i class="fas fa-qrcode" style="font-size:5rem;color:#dee2e6;"></i>
                            <p class="mt-3 mb-0">Belum ada string QRIS.<br>Paste string QRIS di form sebelah kiri untuk melihat preview.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info Cara Kerja --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3" style="color:#c9a84c;"><i class="fas fa-flow-cascade me-2"></i>Cara Kerja QRIS</h6>
                    <div class="d-flex align-items-start mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                             style="width:32px;height:32px;background:#c9a84c;color:#0f172a;font-weight:bold;font-size:0.8rem;">1</div>
                        <div>
                            <div class="fw-semibold small">Admin terbitkan tagihan</div>
                            <div class="text-muted" style="font-size:0.8rem;">Nominal otomatis masuk ke QR</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                             style="width:32px;height:32px;background:#c9a84c;color:#0f172a;font-weight:bold;font-size:0.8rem;">2</div>
                        <div>
                            <div class="fw-semibold small">Klien scan QR Code</div>
                            <div class="text-muted" style="font-size:0.8rem;">Via DANA, GoPay, OVO, m-Banking QRIS</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                             style="width:32px;height:32px;background:#c9a84c;color:#0f172a;font-weight:bold;font-size:0.8rem;">3</div>
                        <div>
                            <div class="fw-semibold small">Klien upload bukti bayar</div>
                            <div class="text-muted" style="font-size:0.8rem;">Screenshot bukti transfer diunggah</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                             style="width:32px;height:32px;background:#c9a84c;color:#0f172a;font-weight:bold;font-size:0.8rem;">4</div>
                        <div>
                            <div class="fw-semibold small">Admin konfirmasi Lunas</div>
                            <div class="text-muted" style="font-size:0.8rem;">Status pembayaran diperbarui</div>
                        </div>
                    </div>
                    <div class="mt-3 p-2 rounded small" style="background:#fff3cd;color:#856404;">
                        <i class="fas fa-info-circle me-1"></i>
                        Didukung oleh <strong>buatqris.site</strong> — layanan gratis generator QRIS dinamis Indonesia.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

