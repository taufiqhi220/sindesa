<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSuratIzinKeramaian extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'detail_surat_izin_keramaian';

    protected $fillable = [
        'surat_id', 'no_hp_pemohon', 'keperluan', 'tanggal_kegiatan',
        'waktu_kegiatan', 'lokasi_kegiatan', 'path_ktp', "path_kk", 'path_lain',
    ];

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class);
    }
}