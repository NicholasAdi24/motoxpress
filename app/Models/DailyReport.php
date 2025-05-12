<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $fillable = [
        'tanggal',
        'jumlah_transaksi',
        'pemasukan_total',
        'item_terjual',
        'total_hpp'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'item_terjual' => 'array', // ini penting agar Laravel tahu ini JSON
        'pemasukan_total' => 'decimal:2',
    ];
}
