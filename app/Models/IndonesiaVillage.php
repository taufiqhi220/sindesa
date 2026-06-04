<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndonesiaVillage extends Model
{
    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'indonesia_villages';

    /**
     * Menunjukkan bahwa primary key bukan auto-increment.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Tipe data dari primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Nama primary key.
     *
     * @var string
     */
    protected $primaryKey = 'code';

    // TAMBAHKAN RELASI INI
    public function kantor()
    {
        return $this->belongsTo(Kantor::class, 'kantor_id', 'id');
    }
}
