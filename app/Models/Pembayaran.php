<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan',
        'total_harga',
        'metode_pembayaran',
        'status_pembayaran',
        'bukti_pembayaran',
        'waktu_pembayaran'
    ];

    protected $casts = [
        'pesanan' => 'array',
        'waktu_pembayaran' => 'datetime'
    ];
}
