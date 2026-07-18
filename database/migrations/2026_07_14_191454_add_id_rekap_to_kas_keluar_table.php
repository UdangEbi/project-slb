<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('kas_keluar', function (Blueprint $table) {

            $table->unsignedBigInteger('id_rekap')
                  ->nullable()
                  ->after('keterangan');

            $table->foreign('id_rekap')
                  ->references('id_rekap')
                  ->on('rekap_kasir')
                  ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('kas_keluar', function (Blueprint $table) {

            $table->dropForeign(['id_rekap']);
            $table->dropColumn('id_rekap');

        });
    }
};
