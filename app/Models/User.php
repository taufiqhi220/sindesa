<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use App\Notifications\CustomResetPassword;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'name',
        'nik',
        'no_kk', 
        'nip', 
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_lengkap',
        'rt_rw',
        'provinsi',
        'kota',
        'kecamatan',
        'kelurahan_desa',
        'agama',
        'status_perkawinan',
        'pekerjaan',
        'kewarganegaraan',
        'phone', 
        'email',
        'password',
        'avatar', 
        'role',
        'no_hp',        
        'foto_profil',
        'status',    
        'province_code',
        'city_code',
        'district_code',
        'village_code',
        'ttd_path',
        'foto_ktp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function province()
    {
        return $this->belongsTo(IndonesiaProvince::class, 'provinsi', 'code');
    }

    public function city()
    {
        return $this->belongsTo(IndonesiaCity::class, 'kota', 'code');
    }

    public function district()
    {
        return $this->belongsTo(IndonesiaDistrict::class, 'kecamatan', 'code');
    }

    public function village()
    {
        return $this->belongsTo(IndonesiaVillage::class, 'kelurahan_desa', 'code');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Log semua kolom yang ada di $fillable jika ada perubahan
            ->logFillable()
            // Hanya catat kolom yang benar-benar berubah nilainya
            ->logOnlyDirty()
            // Abaikan pencatatan jika tidak ada perubahan sama sekali
            ->dontLogEmptyChanges()
            // Beri nama log-nya (contoh: Manajemen Pengguna)
            ->useLogName('Manajemen Pengguna');
    }

    /**
     * ENH-03: Accessor untuk label jabatan.
     * Operator tanpa NIP = "Operator Desa", Kades tanpa NIP = "Kepala Desa".
     */
    public function getJabatanAttribute()
    {
        if ($this->role === 'operator') {
            return $this->nip ? 'NIP. ' . $this->nip : 'Operator Desa';
        }
        if ($this->role === 'kades') {
            return $this->nip ? 'NIP. ' . $this->nip : 'Kepala Desa';
        }
        return '';
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }
}