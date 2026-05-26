<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $fillable = [
        'toko_id', 'user_id',
        'latitude_sales', 'longitude_sales', 'accuracy_sales',
        'jarak_meter', 'status',
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}