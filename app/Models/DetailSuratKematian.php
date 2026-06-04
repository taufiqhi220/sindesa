<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSuratKematian extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'detail_surat_kematian';


    protected $fillable = [
        'surat_id', 'nama_jenazah', 'jenis_kelamin_jenazah', 'tempat_lahir_jenazah', 'tanggal_lahir_jenazah',
        'agama_jenazah', 'pekerjaan_jenazah', 'alamat_jenazah', 'tanggal_wafat', 'tempat_kematian', 'penyebab_kematian',
        'path_ktp_jenazah', 'path_ktp_ahli_waris', 'path_kk', 'path_lain',
    ];

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class);
    }
}