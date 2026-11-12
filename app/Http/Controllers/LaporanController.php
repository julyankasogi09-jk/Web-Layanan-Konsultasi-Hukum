<?php

namespace App\Http\Controllers;

use App\Models\FormulirKonsultasi;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function kasus(Request $request)
    {
        $query = FormulirKonsultasi::with(['klien', 'kategori', 'pengacara']);
        if ($request->status_kasus) {
            $query->where('status_kasus', $request->status_kasus);
        }
        if ($request->bulan) {
            $query->whereMonth('created_at', $request->bulan);
        }
        if ($request->tahun) {
            $query->whereYear('created_at', $request->tahun);
        }
        $data = $query->latest()->get();
        return view('laporan.kasus', compact('data'));
    }

    public function pembayaran(Request $request)
    {
        $query = Pembayaran::with(['formulir.klien']);
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->bulan) {
            $query->whereMonth('created_at', $request->bulan);
        }
        if ($request->tahun) {
            $query->whereYear('created_at', $request->tahun);
        }
        $data         = $query->latest()->get();
        $totalLunas   = $data->where('status', 'lunas')->sum('jumlah_tagihan');
        $totalTagihan = $data->sum('jumlah_tagihan');
        return view('laporan.pembayaran', compact('data', 'totalLunas', 'totalTagihan'));
    }
}

