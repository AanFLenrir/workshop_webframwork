<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = ['kartu_nfc_id', 'mata_kuliah', 'tanggal', 'jam_masuk', 'status', 'serial_number'];

    public function kartu()
    {
        return $this->belongsTo(KartuNfc::class, 'kartu_nfc_id');
    }
}