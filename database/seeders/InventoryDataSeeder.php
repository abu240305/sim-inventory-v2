<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@gmail.com')->first() ?? User::first();
        $adminId = $admin ? $admin->id : 1;

        // 1. Kategori Inventaris BBWS
        $kategoris = [
            'Peralatan Kualitas Air & Laboratorium',
            'Peralatan Hidrologi & Pos Duga Air',
            'Reagen & Bahan Kimia Uji',
            'Material Konstruksi & Pemeliharaan Pos',
            'Peralatan Kerja Lapangan & Mekanikal',
        ];

        $kategoriIds = [];
        foreach ($kategoris as $namaKat) {
            $kat = Kategori::updateOrCreate(
                ['nama_kategori' => $namaKat],
                ['created_at' => now(), 'updated_at' => now()]
            );
            $kategoriIds[$namaKat] = $kat->id;
        }

        // 2. Master Barang (Alat vs Bahan)
        $items = [
            // ALAT / INSTRUMEN
            [
                'kode_barang' => 'ALT-001',
                'nama_barang' => 'Combo Water Quality Meter dan Turbidity',
                'jenis' => 'alat',
                'merk_tipe' => 'Horiba U-50 Multiparameter',
                'satuan' => 'Unit',
                'kategori_id' => $kategoriIds['Peralatan Kualitas Air & Laboratorium'],
                'kondisi' => 'Baik',
                'lokasi' => 'Lab Kualitas Air - Lemari A',
                'jumlah' => 4,
            ],
            [
                'kode_barang' => 'ALT-002',
                'nama_barang' => 'Current Meter Flowatch (Pengukur Arus Sungai)',
                'jenis' => 'alat',
                'merk_tipe' => 'Flowatch FL-03 Swiss',
                'satuan' => 'Unit',
                'kategori_id' => $kategoriIds['Peralatan Hidrologi & Pos Duga Air'],
                'kondisi' => 'Baik',
                'lokasi' => 'Gudang Alat Hidrologi',
                'jumlah' => 3,
            ],
            [
                'kode_barang' => 'ALT-003',
                'nama_barang' => 'Automatic Water Level Recorder (AWLR)',
                'jenis' => 'alat',
                'merk_tipe' => 'OTT Orpheus Mini Pressure Sensor',
                'satuan' => 'Unit',
                'kategori_id' => $kategoriIds['Peralatan Hidrologi & Pos Duga Air'],
                'kondisi' => 'Baik',
                'lokasi' => 'Gudang Instrumen - Rak 2',
                'jumlah' => 2,
            ],
            [
                'kode_barang' => 'ALT-004',
                'nama_barang' => 'GPS Handheld Navigator',
                'jenis' => 'alat',
                'merk_tipe' => 'Garmin GPSMAP 64s',
                'satuan' => 'Unit',
                'kategori_id' => $kategoriIds['Peralatan Kerja Lapangan & Mekanikal'],
                'kondisi' => 'Baik',
                'lokasi' => 'Lemari Khusus Survei',
                'jumlah' => 5,
            ],
            [
                'kode_barang' => 'ALT-005',
                'nama_barang' => 'Vertical Water Sampler (Kemmerer)',
                'jenis' => 'alat',
                'merk_tipe' => 'Wildco 1.2 Liter Acrylic',
                'satuan' => 'Unit',
                'kategori_id' => $kategoriIds['Peralatan Kualitas Air & Laboratorium'],
                'kondisi' => 'Baik',
                'lokasi' => 'Lab Kualitas Air - Lemari B',
                'jumlah' => 3,
            ],
            [
                'kode_barang' => 'ALT-006',
                'nama_barang' => 'Mesin Genset Portable 3 kVA',
                'jenis' => 'alat',
                'merk_tipe' => 'Honda EP2500CX 4-Tak',
                'satuan' => 'Unit',
                'kategori_id' => $kategoriIds['Peralatan Kerja Lapangan & Mekanikal'],
                'kondisi' => 'Baik',
                'lokasi' => 'Bengkel & Pool Lapangan',
                'jumlah' => 2,
            ],
            [
                'kode_barang' => 'ALT-007',
                'nama_barang' => 'Bor Rotary Hammer Beton Listrik',
                'jenis' => 'alat',
                'merk_tipe' => 'Makita HR2470 24mm',
                'satuan' => 'Unit',
                'kategori_id' => $kategoriIds['Peralatan Kerja Lapangan & Mekanikal'],
                'kondisi' => 'Baik',
                'lokasi' => 'Gudang Workshop',
                'jumlah' => 3,
            ],

            // BAHAN / MATERIAL
            [
                'kode_barang' => 'BHN-001',
                'nama_barang' => 'Reagen Buffer Solution pH 7.00',
                'jenis' => 'bahan',
                'merk_tipe' => 'Merck Certipur 500 mL',
                'satuan' => 'Botol',
                'kategori_id' => $kategoriIds['Reagen & Bahan Kimia Uji'],
                'kondisi' => 'Baik',
                'lokasi' => 'Lemari Reagen B3',
                'jumlah' => 12,
            ],
            [
                'kode_barang' => 'BHN-002',
                'nama_barang' => 'Reagen Buffer Solution pH 4.01',
                'jenis' => 'bahan',
                'merk_tipe' => 'Merck Certipur 500 mL',
                'satuan' => 'Botol',
                'kategori_id' => $kategoriIds['Reagen & Bahan Kimia Uji'],
                'kondisi' => 'Baik',
                'lokasi' => 'Lemari Reagen B3',
                'jumlah' => 10,
            ],
            [
                'kode_barang' => 'BHN-003',
                'nama_barang' => 'Filter Membrane 0.45 Mikron',
                'jenis' => 'bahan',
                'merk_tipe' => 'Whatman 47mm (Isi 100 Lembar)',
                'satuan' => 'Box',
                'kategori_id' => $kategoriIds['Reagen & Bahan Kimia Uji'],
                'kondisi' => 'Baik',
                'lokasi' => 'Lab Kualitas Air',
                'jumlah' => 8,
            ],
            [
                'kode_barang' => 'BHN-004',
                'nama_barang' => 'Larutan Standar Turbidity 100 NTU',
                'jenis' => 'bahan',
                'merk_tipe' => 'Hanna Instruments HI93703-10',
                'satuan' => 'Botol',
                'kategori_id' => $kategoriIds['Reagen & Bahan Kimia Uji'],
                'kondisi' => 'Baik',
                'lokasi' => 'Lemari Standar Kalibrasi',
                'jumlah' => 6,
            ],
            [
                'kode_barang' => 'BHN-005',
                'nama_barang' => 'Pipa Galvanis Medium 2 Inch',
                'jenis' => 'bahan',
                'merk_tipe' => 'Spindo SNI (Panjang 6 Meter)',
                'satuan' => 'Batang',
                'kategori_id' => $kategoriIds['Material Konstruksi & Pemeliharaan Pos'],
                'kondisi' => 'Baik',
                'lokasi' => 'Area Terbuka Gudang Logistik',
                'jumlah' => 20,
            ],
            [
                'kode_barang' => 'BHN-006',
                'nama_barang' => 'Semen Portland Komposit',
                'jenis' => 'bahan',
                'merk_tipe' => 'Semen Tonasa 50 Kg',
                'satuan' => 'Sak',
                'kategori_id' => $kategoriIds['Material Konstruksi & Pemeliharaan Pos'],
                'kondisi' => 'Baik',
                'lokasi' => 'Gudang Material Tertutup',
                'jumlah' => 35,
            ],
            [
                'kode_barang' => 'BHN-007',
                'nama_barang' => 'Cat Besi Anti Karat Weathercoat',
                'jenis' => 'bahan',
                'merk_tipe' => 'Jotun Alkyd Paint (5 Liter)',
                'satuan' => 'Pcs',
                'kategori_id' => $kategoriIds['Material Konstruksi & Pemeliharaan Pos'],
                'kondisi' => 'Baik',
                'lokasi' => 'Gudang Cat & Pelumas',
                'jumlah' => 10,
            ],
        ];

        $barangMap = [];
        foreach ($items as $item) {
            $b = Barang::updateOrCreate(
                ['kode_barang' => $item['kode_barang']],
                $item
            );
            $barangMap[$item['kode_barang']] = $b;
        }

        // 3. Sample Transaksi Masuk
        BarangMasuk::updateOrCreate(
            ['barang_id' => $barangMap['ALT-001']->id, 'tanggal' => '2026-08-15'],
            [
                'jumlah' => 2,
                'sumber' => 'DIPA APBN Balai 2026 (Pengadaan Lab)',
                'keterangan' => 'Penerimaan alat baru lengkap dengan probe multiparameter',
                'user_id' => $adminId,
            ]
        );

        BarangMasuk::updateOrCreate(
            ['barang_id' => $barangMap['BHN-001']->id, 'tanggal' => '2026-08-20'],
            [
                'jumlah' => 12,
                'sumber' => 'Pengadaan Rutin Laboratorium',
                'keterangan' => 'Buffer solution untuk kalibrasi pH meter semester 2',
                'user_id' => $adminId,
            ]
        );

        // 4. Sample Transaksi Keluar (Dokumen 1 Form Keluar Alat/Barang)
        BarangKeluar::updateOrCreate(
            ['kode_transaksi' => 'TRK-20260905-0001'],
            [
                'barang_id' => $barangMap['ALT-002']->id,
                'jumlah' => 1,
                'tanggal' => '2026-09-05',
                'penerima' => 'Ir. Muhammad Aris, S.T.',
                'bagian' => 'Tim Pemantau Debit Sungai Jeneberang',
                'dibawa_oleh' => 'Ir. Muhammad Aris, S.T.',
                'diperiksa_oleh' => 'Admin Inventaris',
                'disetujui_oleh' => 'A. Zafriyadi, S.T.',
                'keperluan' => 'Pengukuran debit aliran sungai pos hidrologi Bendung Bisua',
                'keterangan' => 'Kondisi alat baik, baling-baling putar normal',
                'user_id' => $adminId,
            ]
        );

        // 5. Sample Berita Acara Peminjaman Alat (Dokumen 2 Real Sesuai Foto UNM)
        $pj = Peminjaman::updateOrCreate(
            ['nomor_surat' => 'UM0102/B/BBWS11.6.3/2026/31'],
            [
                'hari_surat' => 'Rabu',
                'tanggal_pinjam' => '2026-09-09',
                'rencana_kembali' => '2026-09-10',
                'nama_penyerah' => 'A. Zafriyadi, S.T.',
                'jabatan_penyerah' => 'Ketua Satuan Pengelola Peralatan – Unit Hidrologi dan Kualitas Air',
                'instansi_penyerah' => 'BBWS Pompengan Jeneberang',
                'nama_peminjam' => 'Dr. Eng. Sulfikar, S.Si., M.T.',
                'jabatan_peminjam' => 'Dosen',
                'instansi_peminjam' => 'Jurusan Kimia, FMIPA Universitas Negeri Makassar',
                'kontak_peminjam' => '0811419080',
                'keperluan' => 'Sampling kualitas air pada penelitian "Pemanfaatan Siklus Nutrien Ekosistem Nipah Fruticans Wurmb (Nipah) untuk bioremediasi lahan basah perkotaan".',
                'status' => 'dikembalikan',
                'tanggal_kembali_aktual' => '2026-09-10',
                'hari_kembali' => 'Kamis',
                'kondisi_kembali' => 'Baik',
                'nama_penyerah_kembali' => 'Nurasyiah',
                'nama_penerima_kembali' => 'A. Zafriyadi, S.T.',
                'catatan_kembali' => 'Sudah dikembalikan pada hari Kamis tgl 10 September 2026 dalam kondisi baik.',
                'user_id' => $adminId,
            ]
        );

        PeminjamanDetail::updateOrCreate(
            ['peminjaman_id' => $pj->id, 'barang_id' => $barangMap['ALT-001']->id],
            [
                'jumlah' => 1,
                'kondisi_pinjam' => 'Baik',
                'keterangan' => 'Lengkap kabel sensor & probe terkalibrasi',
            ]
        );

        // 6. Sample Peminjaman yang Sedang Berjalan (Aktif)
        $pj2 = Peminjaman::updateOrCreate(
            ['nomor_surat' => 'UM0102/B/BBWS11.6.3/2026/32'],
            [
                'hari_surat' => 'Senin',
                'tanggal_pinjam' => Carbon::today()->subDays(2)->format('Y-m-d'),
                'rencana_kembali' => Carbon::today()->addDays(2)->format('Y-m-d'),
                'nama_penyerah' => 'A. Zafriyadi, S.T.',
                'jabatan_penyerah' => 'Ketua Satuan Pengelola Peralatan – Unit Hidrologi dan Kualitas Air',
                'instansi_penyerah' => 'BBWS Pompengan Jeneberang',
                'nama_peminjam' => 'Andi Baso Pratama, S.T.',
                'jabatan_peminjam' => 'Tenaga Ahli Hidrologi Konsultan',
                'instansi_peminjam' => 'PT. Ciriajasa Cipta Mandiri',
                'kontak_peminjam' => '082190887766',
                'keperluan' => 'Inspeksi berkala dan pemetaan elevasi koordinat patok duga air DAS Maros.',
                'status' => 'dipinjam',
                'user_id' => $adminId,
            ]
        );

        PeminjamanDetail::updateOrCreate(
            ['peminjaman_id' => $pj2->id, 'barang_id' => $barangMap['ALT-004']->id],
            [
                'jumlah' => 1,
                'kondisi_pinjam' => 'Baik',
                'keterangan' => 'GPS + kabel data + baterai cadangan',
            ]
        );
    }
}
