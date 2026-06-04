<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_surats', function (Blueprint $table) {
            $table->id();
            $table->string('logo_path')->nullable(); // Untuk path gambar logo
            $table->string('header_1')->default('PEMERINTAH KABUPATEN PINRANG');
            $table->string('header_2')->default('KECAMATAN DUAMPANUA');
            $table->string('nama_desa')->default('DESA BUTTU SAWE');
            $table->string('alamat')->default('Jalan Poros Kamali Rajang, Desa Buttu Sawe Tlp. 0421.......................Kode Pos 91253');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_surats');
    }
};