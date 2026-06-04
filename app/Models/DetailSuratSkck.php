<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSuratSkck extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'detail_surat_skck';

    protected $fillable = [
        'surat_id', 'nama_kepala_keluarga', 'keperluan',
        'path_ktp', 'path_kk', 'path_lain',
    ];

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class);
    }
}