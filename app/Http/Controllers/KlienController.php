<?php

namespace App\Http\Controllers;

use App\Models\FormulirKonsultasi;
use App\Models\KategoriKasus;
use App\Models\Pembayaran;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KlienController extends Controller
{
    public function dashboard()
    {
        $klien = Auth::user();
        $formulirList = FormulirKonsultasi::where('klien_id', $klien->id)
            ->with(['kategori', 'pembayaran'])
            ->latest()
            ->get();
        $totalKasus        = $formulirList->count();
        $kasusAktif        = $formulirList->where('status_kasus', 'dalam_progres')->count();
        $kasusSelesai      = $formulirList->where('status_kasus', 'selesai')->count();
        $menantiVerifikasi = $formulirList->where('status_verifikasi', 'pending')->count();

        return view('klien.dashboard', compact(
            'formulirList', 'totalKasus', 'kasusAktif', 'kasusSelesai', 'menantiVerifikasi'
        ));
    }

    public function ajukanKonsultasi()
    {
        $kategoriList = KategoriKasus::all();
        return view('klien.ajukan_konsultasi', compact('kategoriList'));
    }

    public function simpanKonsultasi(Request $request)
    {
        $request->validate([
            'kategori_id'            => 'required|exists:kategori_kasus,id',
            'tempat_lahir'           => 'nullable|string|max:100',
            'tgl_lahir'              => 'nullable|date',
            'jenis_kelamin'          => 'nullable|in:L,P',
            'agama'                  => 'nullable|string|max:50',
            'pekerjaan'              => 'nullable|string|max:100',
            'uraian_singkat_perkara' => 'required|string|min:20',
            'bukti_awal'             => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'uraian_singkat_perkara.required' => 'Uraian singkat perkara wajib diisi.',
            'uraian_singkat_perkara.min'      => 'Uraian perkara minimal 20 karakter.',
        ]);

        $noPermohonan = 'REG/' . date('Y') . '/' . strtoupper(date('M')) . '/' . str_pad(FormulirKonsultasi::count() + 1, 4, '0', STR_PAD_LEFT);

        $buktiPath = null;
        if ($request->hasFile('bukti_awal')) {
            $buktiPath = $request->file('bukti_awal')->store('bukti_awal', 'public');
        }

        FormulirKonsultasi::create([
            'no_permohonan'          => $noPermohonan,
            'klien_id'               => Auth::id(),
            'kategori_id'            => $request->kategori_id,
            'tempat_lahir'           => $request->tempat_lahir,
            'tgl_lahir'              => $request->tgl_lahir,
            'jenis_kelamin'          => $request->jenis_kelamin,
            'agama'                  => $request->agama,
            'pekerjaan'              => $request->pekerjaan,
            'uraian_singkat_perkara' => $request->uraian_singkat_perkara,
            'bukti_awal'             => $buktiPath,
        ]);

        return redirect()->route('klien.dashboard')->with('success', 'Permohonan konsultasi berhasil diajukan! Nomor: ' . $noPermohonan);
    }

    public function detailKasus($id)
    {
        $formulir = FormulirKonsultasi::where('klien_id', Auth::id())
            ->with(['kategori', 'pengacara', 'pembayaran', 'kontrak', 'pesanChat.pengirim'])
            ->findOrFail($id);

        // QRIS Payment Gateway
        $qrisEnabled  = PaymentSetting::getValue('qris_enabled', '0') === '1';
        $qrisNote     = PaymentSetting::getValue('qris_note', '');
        $staticQris   = PaymentSetting::getValue('qris_string', '');
        $merchantName = PaymentSetting::getValue('qris_merchant_name', 'Ahmad Handoko Law Office');
        $qrisString   = '';
        $qrImageUrl   = '';

        if ($qrisEnabled && $staticQris && $formulir->pembayaran) {
            $nominal    = (float) $formulir->pembayaran->jumlah_tagihan;
            // Generate QRIS Dinamis standar EMVCo dengan nominal terisi otomatis
            $qrisString = PaymentSetting::makeDynamicQris($staticQris, $nominal);
            $qrImageUrl = PaymentSetting::getQrImageUrl($qrisString);
        }

        return view('klien.detail_kasus', compact(
            'formulir',
            'qrisEnabled',
            'qrisString',
            'qrImageUrl',
            'qrisNote',
            'merchantName'
        ));
    }

    public function uploadBuktiBayar(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'bukti_bayar.required' => 'File bukti pembayaran wajib diunggah.',
            'bukti_bayar.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $formulir   = FormulirKonsultasi::where('klien_id', Auth::id())->findOrFail($id);
        $pembayaran = Pembayaran::where('formulir_id', $id)->firstOrFail();

        if ($pembayaran->bukti_bayar) {
            Storage::disk('public')->delete($pembayaran->bukti_bayar);
        }

        $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        $pembayaran->update([
            'bukti_bayar' => $path,
            'status'      => 'menunggu_verifikasi',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi sekretaris.');
    }
}
