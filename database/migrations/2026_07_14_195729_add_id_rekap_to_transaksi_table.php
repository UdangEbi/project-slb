<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {

            $table->unsignedBigInteger('id_rekap')
                ->nullable()
                ->after('status');

            $table->foreign('id_rekap')
                ->references('id_rekap')
                ->on('rekap_kasir')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {

            $table->dropForeign(['id_rekap']);
            $table->dropColumn('id_rekap');

        });
    }
};
