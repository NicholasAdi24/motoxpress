<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->json('pesanan'); // Menyimpan daftar pesanan dalam format JSON
            $table->decimal('total_harga', 10, 2);
            $table->enum('metode_pembayaran', ['cash', 'qris']);
            $table->enum('status_pembayaran', ['paid', 'unpaid'])->default('unpaid');
            $table->string('bukti_pembayaran')->nullable(); // Menyimpan gambar bukti pembayaran jika menggunakan QRIS
            $table->timestamp('waktu_pembayaran')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
};

