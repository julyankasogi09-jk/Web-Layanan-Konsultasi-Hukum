<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('payment_settings')->insert([
            [
                'key'        => 'qris_string',
                'value'      => '',
                'label'      => 'String QRIS Statis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key'        => 'qris_merchant_name',
                'value'      => 'Ahmad Handoko Law Office',
                'label'      => 'Nama Merchant QRIS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key'        => 'qris_enabled',
                'value'      => '1',
                'label'      => 'Aktifkan QRIS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key'        => 'qris_note',
                'value'      => 'Pembayaran dapat dilakukan melalui DANA, GoPay, OVO, ShopeePay, BRI, BCA, Mandiri, dan e-wallet/bank lainnya yang mendukung QRIS.',
                'label'      => 'Catatan Pembayaran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
