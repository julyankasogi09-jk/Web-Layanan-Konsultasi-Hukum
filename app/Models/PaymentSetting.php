<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $table    = 'payment_settings';
    protected $fillable = ['key', 'value', 'label'];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Konversi string QRIS statis menjadi QRIS Dinamis (EMVCo standard) dengan nominal otomatis.
     */
    public static function makeDynamicQris(string $staticQris, ?float $nominal = null): string
    {
        $staticQris = trim($staticQris);
        if (empty($staticQris) || !$nominal || $nominal <= 0) {
            return $staticQris;
        }

        // 1. Ubah 010211 (statis) menjadi 010212 (dinamis)
        if (str_starts_with($staticQris, '000201010211')) {
            $qris = '000201010212' . substr($staticQris, 12);
        } else {
            $qris = $staticQris;
        }

        // 2. Buang tag 6304 + 4 hex CRC yang ada di belakang (total 8 karakter)
        if (str_contains($qris, '6304')) {
            $posCrc = strrpos($qris, '6304');
            $qrisWithoutCrc = substr($qris, 0, $posCrc);
        } else {
            $qrisWithoutCrc = substr($qris, 0, -4);
        }

        // 3. Format tag 54 (Nominal transaksi)
        $nominalStr = (string)(int)round($nominal);
        $lenNominal = str_pad(strlen($nominalStr), 2, '0', STR_PAD_LEFT);
        $tag54 = '54' . $lenNominal . $nominalStr;

        // Jika tag 54 sudah ada sebelumnya, ganti. Jika belum, sisipkan sebelum tag 58 (Country Code 5802ID)
        if (preg_match('/54\d{2}\d+/', $qrisWithoutCrc, $matches)) {
            $qrisWithNominal = preg_replace('/54\d{2}\d+/', $tag54, $qrisWithoutCrc, 1);
        } else {
            $pos58 = strpos($qrisWithoutCrc, '5802ID');
            if ($pos58 !== false) {
                $qrisWithNominal = substr($qrisWithoutCrc, 0, $pos58) . $tag54 . substr($qrisWithoutCrc, $pos58);
            } else {
                $qrisWithNominal = $qrisWithoutCrc . $tag54;
            }
        }

        // 4. Tambahkan tag 6304
        $payloadForCrc = $qrisWithNominal . '6304';

        // 5. Hitung CRC16-CCITT (Standar QRIS / EMVCo)
        $crc = static::crc16Ccitt($payloadForCrc);

        return $payloadForCrc . strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    /**
     * Hitung CRC16-CCITT (Polynomial 0x1021, Initial 0xFFFF)
     */
    public static function crc16Ccitt(string $data): int
    {
        $crc = 0xFFFF;
        $len = strlen($data);
        for ($i = 0; $i < $len; $i++) {
            $crc ^= (ord($data[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = (($crc << 1) ^ 0x1021) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }
        return $crc;
    }

    /**
     * Dapatkan URL gambar QR code yang handal (menggunakan QRServer API / buatqris sebagai fallback).
     */
    public static function getQrImageUrl(string $qrisString): string
    {
        if (empty($qrisString)) {
            return '';
        }
        // Menggunakan api.qrserver.com yang sangat cepat, handal, dan tanpa limit
        return 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data=' . urlencode($qrisString);
    }
}
