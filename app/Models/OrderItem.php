<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'barang_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
