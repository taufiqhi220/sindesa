<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSuratPerubahanData extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = "detail_surat_perubahan_data";

    protected $fillable = [
        'surat_id', 'jenis_perubahan', 'perubahan_lainnya', 'data_semula',
        'data_menjadi', 'dasar_nomor_surat', 'dasar_tanggal_surat',
        'path_ktp', 'path_kk', 'path_lain',
    ];

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class);
    }
}