<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class);
    }
}
