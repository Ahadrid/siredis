<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat'; 

    protected $fillable = [
        'kode_obat', 
        'nama_obat', 
        'satuan', 
        'stok', 
        'harga'
    ];

    public function reseps() { return $this->hasMany(ResepObat::class); }
}
