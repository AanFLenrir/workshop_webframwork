<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    protected $fillable = ['kode', 'nama', 'aktif'];

    public function antrians()
    {
        return $this->hasMany(Antrian::class);
    }

    public function nextNomor(): int
    {
        return ($this->antrians()->max('nomor') ?? 0) + 1;
    }
}