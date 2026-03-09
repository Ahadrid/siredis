<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResepObat extends Model
{
    protected $table = 'resep_obat'; 
    
    protected $fillable = [
        'rekam_medis_id', 
        'obat_id', 
        'jumlah', 
        'aturan_pakai', 
        'catatan'
    ];

    public function rekamMedis() { return $this->belongsTo(RekamMedis::class); }
    public function obat()       { return $this->belongsTo(Obat::class); }
}
