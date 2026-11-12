<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function pembayaran()
    {
        $settings = PaymentSetting::pluck('value', 'key');
        return view('admin.pengaturan_pembayaran', compact('settings'));
    }

    public function simpanPembayaran(Request $request)
    {
        $request->validate([
            'qris_string'        => 'nullable|string|max:5000',
            'qris_merchant_name' => 'required|string|max:100',
            'qris_note'          => 'nullable|string|max:500',
        ]);

        PaymentSetting::setValue('qris_string',        $request->qris_string ?? '');
        PaymentSetting::setValue('qris_merchant_name', $request->qris_merchant_name);
        PaymentSetting::setValue('qris_enabled',       $request->has('qris_enabled') ? '1' : '0');
        PaymentSetting::setValue('qris_note',          $request->qris_note ?? '');

        return back()->with('success', 'Pengaturan QRIS berhasil disimpan!');
    }
}

