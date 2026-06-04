<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class JenisSurat extends Model
{
    use HasFactory, LogsActivity;
    
    public $timestamps = false;
    protected $table = 'jenis_surat';

    protected $fillable = [
        'kode_surat', 'nama_surat', 'deskripsi', 'kategori', 'is_active',
    ];

    public function surat(): HasMany
    {
        return $this->hasMany(Surat::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('Master Jenis Surat');
    }
}