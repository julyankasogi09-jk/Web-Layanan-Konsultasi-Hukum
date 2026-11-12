<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kontrak_kesepakatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulir_id')->constrained('formulir_konsultasi')->onDelete('cascade');
            $table->string('berkas_kontrak', 255);
            $table->text('keterangan')->nullable();
            $table->string('status_sidang', 100)->default('Pemberkasan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kontrak_kesepakatan');
    }
};

