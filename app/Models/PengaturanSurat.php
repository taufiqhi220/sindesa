<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class PengaturanSurat extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'logo_path', 'header_1', 'header_2', 'nama_desa', 'alamat'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('Pengaturan Sistem');
    }
}