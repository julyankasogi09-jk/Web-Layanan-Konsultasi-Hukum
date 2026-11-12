<?php

namespace App\Http\Controllers;

use App\Models\FormulirKonsultasi;
use App\Models\KontrakKesepakatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengacaraController extends Controller
{
    public function dashboard()
    {
        $pengacara = Auth::user();
        $perkaraList = FormulirKonsultasi::where('pengacara_id', $pengacara->id)
            ->with(['klien', 'kategori'])
            ->latest()
            ->get();
        $totalDitangani = $perkaraList->count();
        $sedangBerjalan = $perkaraList->where('status_kasus', 'dalam_progres')->count();
        $selesai        = $perkaraList->where('status_kasus', 'selesai')->count();
        return view('pengacara.dashboard', compact('perkaraList', 'totalDitangani', 'sedangBerjalan', 'selesai'));
    }

    public function detailPerkara($id)
    {
        $formulir = FormulirKonsultasi::where('pengacara_id', Auth::id())
            ->with(['klien', 'kategori', 'kontrak', 'pesanChat.pengirim'])
            ->findOrFail($id);
        return view('pengacara.detail_perkara', compact('formulir'));
    }

    public function updateProgres(Request $request, $id)
    {
        $request->validate([
            'status_kasus' => 'required|in:baru,dalam_progres,selesai',
        ]);
        $formulir = FormulirKonsultasi::where('pengacara_id', Auth::id())->findOrFail($id);
        $formulir->update(['status_kasus' => $request->status_kasus]);
        return back()->with('success', 'Status progres perkara berhasil diperbarui.');
    }

    public function uploadKontrak(Request $request, $id)
    {
        $request->validate([
            'berkas_kontrak' => 'required|file|mimes:pdf|max:5120',
            'keterangan'     => 'nullable|string',
            'status_sidang'  => 'nullable|string|max:100',
        ], [
            'berkas_kontrak.required' => 'File kontrak wajib diunggah.',
            'berkas_kontrak.mimes'    => 'File kontrak harus berformat PDF.',
        ]);

        $formulir = FormulirKonsultasi::where('pengacara_id', Auth::id())->findOrFail($id);

        $kontrak = $formulir->kontrak;
        if ($kontrak && $kontrak->berkas_kontrak) {
            Storage::disk('public')->delete($kontrak->berkas_kontrak);
        }

        $path = $request->file('berkas_kontrak')->store('kontrak', 'public');

        KontrakKesepakatan::updateOrCreate(
            ['formulir_id' => $formulir->id],
            [
                'berkas_kontrak' => $path,
                'keterangan'     => $request->keterangan,
                'status_sidang'  => $request->status_sidang ?? 'Pemberkasan',
            ]
        );

        return back()->with('success', 'Berkas kontrak kesepakatan berhasil diunggah.');
    }

    public function updateStatusSidang(Request $request, $id)
    {
        $request->validate(['status_sidang' => 'required|string|max:100']);
        $kontrak = KontrakKesepakatan::where('formulir_id', $id)->firstOrFail();
        $kontrak->update(['status_sidang' => $request->status_sidang]);
        return back()->with('success', 'Status tahapan sidang berhasil diperbarui.');
    }
}

