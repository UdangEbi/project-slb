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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('no_nota', 50);
            $table->dateTime('tanggal');
            $table->unsignedBigInteger('user_id');
            $table->decimal('total', 15, 2);
            $table->decimal('diskon', 15, 2);
            $table->decimal('grand_total', 15, 2);
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'qris']);
            $table->decimal('bayar', 15, 2);
            $table->decimal('kembalian', 15, 2);
            $table->enum('status', ['selesai', 'dibatalkan']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
