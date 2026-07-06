<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('nama_pembeli')->nullable()->after('user_id');
            $table->string('no_tlp')->nullable()->after('nama_pembeli');
            $table->string('instansi')->nullable()->after('no_tlp');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['nama_pembeli', 'no_tlp', 'instansi']);
        });
    }
};