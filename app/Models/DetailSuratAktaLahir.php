<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSuratAktaLahir extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'detail_surat_akta_lahir';

    protected $fillable = [
        'surat_id', 'nama_anak', 'jenis_kelamin_anak', 'tempat_lahir_anak',
        'tanggal_lahir_anak', 'nama_ayah', 'nik_ayah', 'nama_ibu', 'nik_ibu', 
        'path_ktp_ayah', 'path_ktp_ibu', 'path_kk', 'path_lain',
    ];

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class);
    }
}