<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    // Hanya kolom yang benar-benar dibutuhkan boleh diisi secara massal
    protected $fillable = ['user_id', 'aktivitas', 'ip_address'];

    // ATAU kalau mau lebih aman secara spesifik (pilih salah satu, lebih direkomendasikan yang ini):
    // protected $fillable = ['user_id', 'aktivitas', 'ip_address'];

    // Opsional: Relasi ke tabel user jika nanti mau ditampilkan namanya
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}