<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rekap_kasir', function (Blueprint $table) {
            $table->id('id_rekap');
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal');
            $table->integer('total_transaksi');
            $table->decimal('total_penjualan', 15, 2);
            $table->decimal('uang_fisik', 15, 2);
            $table->decimal('selisih', 15, 2);
            $table->text('catatan');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_kasir');
    }
};
