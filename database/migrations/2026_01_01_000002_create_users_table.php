<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('user_roles')->onDelete('restrict');
            $table->string('nama_lengkap', 150);
            $table->string('username', 100)->unique();
            $table->string('email', 150)->unique()->nullable();
            $table->string('password');
            $table->string('no_hp', 25)->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto', 255)->default('default.png');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

