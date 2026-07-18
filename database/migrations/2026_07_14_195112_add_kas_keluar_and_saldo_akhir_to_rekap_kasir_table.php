<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('rekap_kasir', function (Blueprint $table) {

            $table->decimal('total_kas_keluar', 15, 2)
                ->after('total_penjualan');

            $table->decimal('saldo_akhir', 15, 2)
                ->after('total_kas_keluar');

        });
    }

    public function down(): void
    {
        Schema::table('rekap_kasir', function (Blueprint $table) {

            $table->dropColumn([
                'total_kas_keluar',
                'saldo_akhir'
            ]);

        });
    }
};
