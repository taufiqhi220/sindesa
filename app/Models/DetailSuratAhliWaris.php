<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSuratAhliWaris extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'detail_surat_ahli_waris';

    protected $fillable = [
        'surat_id', 'ahli_waris_dari', 'tempat_lahir_almarhum',
        'tanggal_lahir_almarhum', 'alamat_almarhum', 'path_ktp', 'path_lain',
    ];

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class);
    }
}