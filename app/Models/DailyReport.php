<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $fillable = [
        'tanggal',
        'jumlah_transaksi',
        'pemasukan',
        'total_item_terjual',
        'detail_item_terjual',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'detail_item_terjual' => 'array',
    ];
}
