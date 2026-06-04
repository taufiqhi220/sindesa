<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class PengajuanSurat extends Model
{
    use HasFactory, LogsActivity;

    // 1. KUNCI PENTING: Hanya rekam saat di-UPDATE atau di-DELETE. Abaikan saat CREATED (Warga kirim surat).
    protected static $recordEvents = ['updated'];

    protected $fillable = [
        'user_id', 'jenis_surat', 'nomor_surat', 'keperluan',
        'data_tambahan', 'status', 'pesan_penolakan', 'file_surat',
        'is_seen_by_operator', 'is_seen_by_kades', 'metode_ttd', 'token_verifikasi'
    ];

    protected $casts = [
        'data_tambahan' => 'array',
        'is_seen_by_operator' => 'boolean',
        'is_seen_by_kades' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 2. HANYA rekam jika 4 kolom krusial ini yang diubah (Biasanya diubah oleh Operator/Kades)
            ->logOnly(['status', 'nomor_surat', 'pesan_penolakan', 'file_surat'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('Layanan Surat');
    }
}