<?php

namespace App\Http\Controllers;

use App\Models\FormulirKonsultasi;
use App\Models\KategoriKasus;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPerkara      = FormulirKonsultasi::count();
        $perkaraPending    = FormulirKonsultasi::where('status_verifikasi', 'pending')->count();
        $perkaraProses     = FormulirKonsultasi::where('status_kasus', 'dalam_progres')->count();
        $perkaraSelesai    = FormulirKonsultasi::where('status_kasus', 'selesai')->count();
        $totalKlien        = User::whereHas('role', fn($q) => $q->where('nama_role', 'klien'))->count();
        $totalPembayaran   = Pembayaran::where('status', 'lunas')->sum('jumlah_tagihan');
        $perkaraList       = FormulirKonsultasi::with(['klien', 'kategori', 'pengacara'])->latest()->take(10)->get();
        return view('admin.dashboard', compact(
            'totalPerkara', 'perkaraPending', 'perkaraProses', 'perkaraSelesai',
            'totalKlien', 'totalPembayaran', 'perkaraList'
        ));
    }

    public function daftarPerkara(Request $request)
    {
        $query = FormulirKonsultasi::with(['klien', 'kategori', 'pengacara']);
        if ($request->status) {
            $query->where('status_verifikasi', $request->status);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('no_permohonan', 'like', '%' . $request->search . '%')
                  ->orWhereHas('klien', fn($k) => $k->where('nama_lengkap', 'like', '%' . $request->search . '%'));
            });
        }
        $perkaraList = $query->latest()->paginate(10);
        return view('admin.daftar_perkara', compact('perkaraList'));
    }

    public function detailPerkara($id)
    {
        $formulir     = FormulirKonsultasi::with(['klien', 'kategori', 'pengacara', 'pembayaran', 'kontrak'])->findOrFail($id);
        $pengacaraList = User::whereHas('role', fn($q) => $q->where('nama_role', 'pengacara'))->where('is_active', true)->get();
        $kategoriList  = KategoriKasus::all();
        return view('admin.detail_perkara', compact('formulir', 'pengacaraList', 'kategoriList'));
    }

    public function verifikasiPerkara(Request $request, $id)
    {
        $request->validate(['status_verifikasi' => 'required|in:disetujui,ditolak']);
        $formulir = FormulirKonsultasi::findOrFail($id);
        $formulir->update([
            'status_verifikasi' => $request->status_verifikasi,
            'status_kasus'      => $request->status_verifikasi === 'disetujui' ? 'dalam_progres' : $formulir->status_kasus,
        ]);
        $msg = $request->status_verifikasi === 'disetujui' ? 'Permohonan berhasil disetujui.' : 'Permohonan ditolak.';
        return back()->with('success', $msg);
    }

    public function assignPengacara(Request $request, $id)
    {
        $request->validate(['pengacara_id' => 'required|exists:users,id']);
        FormulirKonsultasi::findOrFail($id)->update(['pengacara_id' => $request->pengacara_id]);
        return back()->with('success', 'Pengacara berhasil ditugaskan ke perkara ini.');
    }

    public function jadwalkanTemu(Request $request, $id)
    {
        $request->validate([
            'tgl_waktu_jadwal_temu' => 'required|date|after:now',
            'catatan_jadwal_temu'   => 'nullable|string|max:255',
        ]);
        FormulirKonsultasi::findOrFail($id)->update([
            'tgl_waktu_jadwal_temu' => $request->tgl_waktu_jadwal_temu,
            'catatan_jadwal_temu'   => $request->catatan_jadwal_temu,
            'status_jadwal_temu'    => 'dijadwalkan',
        ]);
        return back()->with('success', 'Jadwal temu konsultasi berhasil ditetapkan.');
    }

    public function terbitkanTagihan(Request $request, $id)
    {
        $request->validate(['jumlah_tagihan' => 'required|numeric|min:1']);
        $formulir = FormulirKonsultasi::findOrFail($id);
        Pembayaran::updateOrCreate(
            ['formulir_id' => $formulir->id],
            ['jumlah_tagihan' => $request->jumlah_tagihan, 'keterangan' => $request->keterangan, 'status' => 'belum_bayar']
        );
        return back()->with('success', 'Tagihan biaya administrasi berhasil diterbitkan.');
    }

    public function konfirmasiPembayaran($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->update(['status' => 'lunas']);
        return back()->with('success', 'Pembayaran telah dikonfirmasi sebagai LUNAS.');
    }

    public function kelolaPembayaran()
    {
        $pembayaranList = Pembayaran::with(['formulir.klien'])->latest()->paginate(10);
        return view('admin.kelola_pembayaran', compact('pembayaranList'));
    }
}

