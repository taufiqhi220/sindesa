<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSuratIzinUsaha extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'detail_surat_izin_usaha';

    protected $fillable = [
        'surat_id', 'nama_usaha', 'merk_usaha', 'jenis_usaha', 'alamat_usaha',
        'path_ktp', 'path_lain',
    ];

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class);
    }
}