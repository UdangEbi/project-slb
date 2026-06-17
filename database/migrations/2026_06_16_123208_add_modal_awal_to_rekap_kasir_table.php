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
        Schema::table('rekap_kasir', function (Blueprint $table) {
            $table->decimal('modal_awal', 15, 2)
                ->after('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekap_kasir', function (Blueprint $table) {
            $table->dropColumn('modal_awal');
        });
    }
};
