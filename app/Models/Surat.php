<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surat';

    protected $fillable = [
        'nomor_surat', 'warga_id', 'jenis_surat_id', 'kantor_id', 'status', 'keterangan_petugas',
        'verified_by', 'verified_at', 'signed_by', 'signed_at', 'path_file_1', 'path_file_2', 'path_file_3',
        'komentar',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'signed_at' => 'datetime',
        'updated_at' => 'datetime', // <-- TAMBAHKAN BARIS INI
    ];

    public function penandaTangan()
    {
        return $this->belongsTo(\App\Models\User::class, 'signed_by');
    }

    // --- RELASI-RELASI ---

    public function warga(): BelongsTo
    {
        return $this->belongsTo(User::class, 'warga_id');
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function petugasVerifikasi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function pejabatPenandatangan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    // --- RELASI KE DETAIL SURAT ---

    public function detailAktaLahir(): HasOne { return $this->hasOne(DetailSuratAktaLahir::class); }
    public function detailPerubahanData(): HasOne { return $this->hasOne(DetailSuratPerubahanData::class); }
    public function detailAhliWaris(): HasOne { return $this->hasOne(DetailSuratAhliWaris::class); }
    public function detailIzinTempatUsaha(): HasOne { return $this->hasOne(DetailSuratIzinTempatUsaha::class); }
    public function detailSkck(): HasOne { return $this->hasOne(DetailSuratSkck::class); }
    public function detailIzinKeramaian(): HasOne { return $this->hasOne(DetailSuratIzinKeramaian::class); }
    public function detailKematian(): HasOne { return $this->hasOne(DetailSuratKematian::class); }
    public function detailPindah(): HasOne { return $this->hasOne(DetailSuratPindah::class); }
    public function detailIzinUsaha(): HasOne { return $this->hasOne(DetailSuratIzinUsaha::class); }
}

