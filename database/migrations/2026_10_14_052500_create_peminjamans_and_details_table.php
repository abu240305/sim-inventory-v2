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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('hari_surat');
            $table->date('tanggal_pinjam');
            $table->date('rencana_kembali');

            // Pihak Pertama (Penyerah Alat / BBWS)
            $table->string('nama_penyerah')->default('A. Zafriyadi, S.T.');
            $table->string('jabatan_penyerah')->default('Ketua Satuan Pengelola Peralatan – Unit Hidrologi dan Kualitas Air');
            $table->string('instansi_penyerah')->default('BBWS Pompengan Jeneberang');

            // Pihak Kedua (Peminjam Alat)
            $table->string('nama_peminjam');
            $table->string('jabatan_peminjam');
            $table->string('instansi_peminjam');
            $table->string('kontak_peminjam')->nullable();

            // Rincian & Keperluan
            $table->text('keperluan');
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');

            // Lembar Pengembalian
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->string('hari_kembali')->nullable();
            $table->string('kondisi_kembali')->nullable();
            $table->string('nama_penyerah_kembali')->nullable();
            $table->string('nama_penerima_kembali')->nullable();
            $table->text('catatan_kembali')->nullable();

            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('peminjaman_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjamans')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs');
            $table->integer('jumlah')->default(1);
            $table->string('kondisi_pinjam')->default('Baik');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_details');
        Schema::dropIfExists('peminjamans');
    }
};
