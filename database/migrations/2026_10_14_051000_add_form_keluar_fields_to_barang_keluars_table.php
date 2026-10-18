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
        Schema::table('barang_keluars', function (Blueprint $table) {
            $table->string('kode_transaksi')->nullable()->after('id');
            $table->integer('jumlah_retur')->default(0)->after('jumlah');
            $table->text('catatan_retur')->nullable()->after('jumlah_retur');
            $table->string('dibawa_oleh')->nullable()->after('penerima');
            $table->string('diperiksa_oleh')->nullable()->after('dibawa_oleh');
            $table->string('disetujui_oleh')->nullable()->after('diperiksa_oleh');
            $table->text('keperluan')->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_keluars', function (Blueprint $table) {
            $table->dropColumn([
                'kode_transaksi',
                'jumlah_retur',
                'catatan_retur',
                'dibawa_oleh',
                'diperiksa_oleh',
                'disetujui_oleh',
                'keperluan',
            ]);
        });
    }
};
