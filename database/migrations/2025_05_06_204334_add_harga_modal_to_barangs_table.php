<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->decimal('harga_modal', 10, 2)->after('harga')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn('harga_modal');
        });
    }
};
