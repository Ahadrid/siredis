<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasien extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pasien';

    protected $fillable = [
        'no_rm', 
        'nama', 
        'jenis_kelamin', 
        'tanggal_lahir',
        'nik', 
        'no_hp', 
        'alamat', 
        'golongan_darah', 
        'riwayat_alergi'
    ];

    protected $casts = ['tanggal_lahir' => 'date'];

    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class);
    }

    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class);
    }

    // Generate No. RM otomatis
    public static function generateNoRM(): string
    {
        $last = static::withTrashed()->latest()->first();
        $next = $last ? ((int) substr($last->no_rm, 2)) + 1 : 1;
        return 'RM-' . str_pad($next, 6, '0', STR_PAD_LEFT);
    }

    public function getUmurAttribute(): int
    {
        return $this->tanggal_lahir->age;
    }

    public function getTanggalLahirIndoAttribute(): string
    {
        return $this->tanggal_lahir->translatedFormat('d-M-Y');
    }
}
