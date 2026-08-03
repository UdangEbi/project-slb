<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Menambahkan kolom status agar shift kasir yang masih "buka"
     * (sudah isi modal awal, belum tutup kas) bisa dilacak dari
     * database, bukan cuma dari session yang hilang saat logout.
     */
    public function up(): void
    {
        Schema::table('rekap_kasir', function (Blueprint $table) {
            $table->enum('status', ['buka', 'tutup'])
                ->default('buka')
                ->after('modal_awal');
        });
    }

    public function down(): void
    {
        Schema::table('rekap_kasir', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};