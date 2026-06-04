<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSuratBedaNama extends Model
{
    protected $table = 'detail_surat_beda_nama';
    protected $guarded = ['id'];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }
}
