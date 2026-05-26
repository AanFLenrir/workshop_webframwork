<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'idbuku';
    public $timestamps = false; // Karena di migrasi tidak ada timestamps()

    protected $fillable = [
        'kode',
        'judul',
        'pengarang',
        'idkategori'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'idkategori', 'idkategori');
    }
}