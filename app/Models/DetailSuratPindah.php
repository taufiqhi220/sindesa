<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSuratPindah extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'detail_surat_pindah';

    protected $fillable = [
        'surat_id', 
        'nomor_kk_asal', 
        'nama_kepala_keluarga_asal', 
        'alamat_asal',
        'alamat_tujuan', 
        'alasan_pindah', 
        'keluarga_yang_pindah',
        'klasifikasi_pindah',
        'path_ktp', 
        'path_kk', 
        'path_lain',
    ];

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class);
    }
}