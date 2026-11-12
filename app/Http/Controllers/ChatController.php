<?php

namespace App\Http\Controllers;

use App\Models\FormulirKonsultasi;
use App\Models\PesanChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function room($formulirId)
    {
        $user    = Auth::user();
        $formulir = FormulirKonsultasi::with(['klien', 'pengacara'])->findOrFail($formulirId);

        // Pastikan user boleh mengakses room ini
        $isKlien     = $formulir->klien_id === $user->id;
        $isPengacara = $formulir->pengacara_id === $user->id;
        $isAdmin     = $user->isAdmin();

        if (!$isKlien && !$isPengacara && !$isAdmin) {
            abort(403, 'Anda tidak memiliki akses ke ruang konsultasi ini.');
        }

        // Tandai semua pesan sebagai sudah dibaca
        PesanChat::where('formulir_id', $formulirId)
            ->where('pengirim_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $pesanList = PesanChat::where('formulir_id', $formulirId)
            ->with('pengirim')
            ->oldest()
            ->get();

        return view('chat.room', compact('formulir', 'pesanList'));
    }

    public function kirim(Request $request, $formulirId)
    {
        $request->validate(['pesan' => 'required|string|max:2000']);

        $user    = Auth::user();
        $formulir = FormulirKonsultasi::findOrFail($formulirId);

        $isKlien     = $formulir->klien_id === $user->id;
        $isPengacara = $formulir->pengacara_id === $user->id;
        $isAdmin     = $user->isAdmin();

        if (!$isKlien && !$isPengacara && !$isAdmin) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $pesan = PesanChat::create([
            'formulir_id' => $formulirId,
            'pengirim_id' => $user->id,
            'pesan'       => $request->pesan,
        ]);

        return response()->json([
            'id'          => $pesan->id,
            'pesan'       => $pesan->pesan,
            'pengirim'    => $user->nama_lengkap,
            'is_me'       => true,
            'created_at'  => $pesan->created_at->format('H:i'),
        ]);
    }

    public function fetch(Request $request, $formulirId)
    {
        $lastId = $request->last_id ?? 0;
        $pesanList = PesanChat::where('formulir_id', $formulirId)
            ->where('id', '>', $lastId)
            ->with('pengirim')
            ->oldest()
            ->get()
            ->map(function ($p) {
                return [
                    'id'         => $p->id,
                    'pesan'      => $p->pesan,
                    'pengirim'   => $p->pengirim->nama_lengkap,
                    'pengirim_id'=> $p->pengirim_id,
                    'avatar'     => strtoupper(substr($p->pengirim->nama_lengkap, 0, 1)),
                    'created_at' => $p->created_at->format('H:i'),
                ];
            });

        return response()->json($pesanList);
    }
}

