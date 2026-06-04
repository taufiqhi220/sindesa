<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KopSurat extends Model
{
    use HasFactory;

    protected $table = 'kop_surat';

    protected $fillable = [
        'kantor_id',
        'nama_instansi',
        'alamat_lengkap',
        'website',
        'email',
        'path_logo',
    ];

    public function kantor()
    {
        return $this->belongsTo(Kantor::class, 'kantor_id');
    }
}
    

