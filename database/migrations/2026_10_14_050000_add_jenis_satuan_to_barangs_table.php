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
        Schema::table('barangs', function (Blueprint $table) {
            $table->enum('jenis', ['alat', 'bahan'])->default('alat')->after('nama_barang');
            $table->string('merk_tipe')->nullable()->after('jenis');
            $table->string('satuan', 50)->default('Unit')->after('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['jenis', 'merk_tipe', 'satuan']);
        });
    }
};
