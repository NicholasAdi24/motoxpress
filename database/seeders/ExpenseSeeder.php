<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $months = [
            '2024-01-15', '2024-02-20', '2024-03-10', '2024-04-05',
            '2024-05-25', '2024-06-17', '2024-07-07'
        ];

        foreach ($months as $tgl) {
            Expense::create([
                'tanggal' => $tgl,
                'deskripsi' => 'Pengeluaran Bulanan',
                'jumlah' => rand(500000, 1500000)
            ]);
        }
    }
}
