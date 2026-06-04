<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSuratIzinTempatUsaha extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'detail_surat_izin_tempat_usaha';

    protected $fillable = [
        'surat_id', 'nama_tempat_usaha', 'alamat_tempat_usaha',
        'path_ktp', 'path_lain',
    ];

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class);
    }
}