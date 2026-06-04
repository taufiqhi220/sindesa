<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_surats', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (siapa yang mengajukan)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Jenis surat (misal: 'pengantar_ktp', 'keterangan_usaha', dll)
            $table->string('jenis_surat'); 
            
            // Nomor surat (Diisi nanti oleh Admin/Kades saat disetujui)
            $table->string('nomor_surat')->nullable(); 
            
            // Keperluan pembuatan surat
            $table->text('keperluan');
            
            // Kolom dinamis (bisa disesuaikan nanti jika ada input khusus tiap surat)
            $table->json('data_tambahan')->nullable(); 
            
            // Status alur surat
            $table->enum('status', [
                'menunggu_verifikasi', // Baru masuk, dicek operator
                'diproses_kades',      // Menunggu TTD Kades
                'selesai',             // Selesai & siap dicetak/diunduh
                'ditolak'              // Ditolak jika data tidak valid
            ])->default('menunggu_verifikasi');
            
            // Pesan jika surat ditolak oleh admin
            $table->text('pesan_penolakan')->nullable();
            
            // Lokasi file PDF jika surat sudah jadi
            $table->string('file_surat')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surats');
    }
};
