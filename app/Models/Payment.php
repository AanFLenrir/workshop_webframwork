<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'midtrans_order_id',
        'transaction_id',
        'payment_type',
        'va_number',
        'qr_code_url',
        'amount',
        'status',
        'paid_at',
        'midtrans_response',
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'paid_at'           => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}