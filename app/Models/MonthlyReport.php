<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    protected $fillable = [
        'bulan',
        'jumlah_transaksi',
        'pemasukan',
    ];

    protected $casts = [
        'bulan' => 'date',
    ];
}
