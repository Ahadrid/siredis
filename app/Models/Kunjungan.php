<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $table = 'kunjungan'; 

    protected $fillable = [
        'no_kunjungan', 
        'pasien_id', 
        'dokter_id',
        'tanggal_kunjungan', 
        'status', 
        'keluhan'
    ];

    protected $casts = ['tanggal_kunjungan' => 'datetime'];

    public function pasien()   { return $this->belongsTo(Pasien::class); }
    public function dokter()   { return $this->belongsTo(User::class, 'dokter_id'); }
    public function rekamMedis() { return $this->hasOne(RekamMedis::class); }

    public static function generateNoKunjungan(): string
    {
        $prefix = 'KNJ' . date('Ymd');
        $count  = static::whereDate('created_at', today())->count() + 1;
        return $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
