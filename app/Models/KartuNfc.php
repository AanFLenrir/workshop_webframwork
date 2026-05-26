<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KartuNfc extends Model
{
    protected $table = 'kartu_nfc';
    protected $fillable = ['serial_number', 'nama_pemilik', 'nim', 'kelas', 'aktif'];

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}