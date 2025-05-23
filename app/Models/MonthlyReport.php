<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    protected $fillable = [
        'bulan',
        'jumlah_transaksi',
        'pemasukan_total',
        'total_hpp'
    ];

    protected $casts = [
        'bulan' => 'date',
        'pemasukan_total' => 'decimal:2',
    ];
}
