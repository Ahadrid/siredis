<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    protected $fillable = [
        'kunjungan_id', 
        'pasien_id', 
        'dokter_id', 
        'anamnesis',
        'pemeriksaan_fisik', 
        'tekanan_darah', 
        'suhu', 
        'nadi',
        'respirasi', 
        'berat_badan', 
        'diagnosis', 
        'kode_icd',
        'tindakan', 
        'catatan'
    ];

    public function kunjungan() { return $this->belongsTo(Kunjungan::class); }
    public function pasien()    { return $this->belongsTo(Pasien::class); }
    public function dokter()    { return $this->belongsTo(User::class, 'dokter_id'); }
    public function resep()    { return $this->hasMany(ResepObat::class); }
}
