<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $fillable = ['poli_id', 'nomor', 'nama', 'status'];

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }
}