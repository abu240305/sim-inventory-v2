<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Keluar Alat / Barang - {{ $transaksi->kode_transaksi ?? $transaksi->id }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 20mm 20mm 20mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.3;
        }
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 18mm 15mm 18mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.3;
        }
        /* Kop Surat Tanpa Border */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin-bottom: 12px;
        }
        .kop-table td {
            border: none;
            padding: 2px 0;
            vertical-align: middle;
        }
        .logo-cell {
            width: 70px;
            text-align: left;
            border: none;
        }
        .instansi-cell {
            text-align: center;
            border: none;
        }
        .instansi-title {
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            letter-spacing: 0.5px;
            line-height: 1.25;
        }
        .instansi-sub {
            font-size: 10pt;
            font-weight: bold;
            margin: 2px 0 0 0;
            line-height: 1.25;
        }
        
        /* Kotak Form Utama */
        .form-box {
            border: 1.5px solid #000;
            padding: 0;
            margin: 0;
        }
        .title-bar {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            padding: 6px;
            border-bottom: 1.5px solid #000;
            letter-spacing: 1px;
            background-color: transparent;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
        }
        .content-table th, .content-table td {
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
            padding: 6px 8px;
            font-size: 10pt;
        }
        .content-table th:last-child, .content-table td:last-child {
            border-right: none;
        }
        .content-table th {
            text-align: center;
            font-weight: bold;
            background-color: transparent;
        }
        .text-center {
            text-align: center;
        }
        .notes-box {
            border-top: 1.5px solid #000;
            padding: 8px 10px;
            min-height: 55px;
            font-size: 10pt;
        }
        .notes-label {
            font-weight: bold;
            margin-bottom: 4px;
        }
        .signature-section {
            border-top: 1.5px solid #000;
            padding: 0;
        }
        .date-line {
            font-size: 10pt;
            padding: 5px 10px;
            border-bottom: 1px solid #000;
            margin-bottom: 0;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        .sign-header {
            width: 33.33%;
            font-size: 10pt;
            font-weight: 500;
            padding: 5px 6px;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
            text-align: center;
        }
        .sign-header:last-child {
            border-right: none;
        }
        .sign-body {
            width: 33.33%;
            vertical-align: bottom;
            font-size: 10pt;
            border-right: 1px solid #000;
            padding: 6px;
            height: 65px;
            text-align: center;
        }
        .sign-body:last-child {
            border-right: none;
        }
        .sign-space {
            height: 48px;
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('assets/img/lo-pu.jpeg');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('assets/img/logo-pu.jpeg');
        }
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <!-- Kop Surat Tanpa Garis Pembungkus / Border -->
    <table class="kop-table">
        <tr>
            <td class="logo-cell">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo PU" style="width: 65px; height: auto;">
                @else
                    <strong>PU</strong>
                @endif
            </td>
            <td class="instansi-cell">
                <div class="instansi-title">KEMENTERIAN PEKERJAAN UMUM</div>
                <div class="instansi-sub">DIREKTORAT JENDERAL SUMBER DAYA AIR</div>
                <div class="instansi-sub">BALAI BESAR WILAYAH SUNGAI POMPENGAN JENEBERANG</div>
                <div class="instansi-sub">UNIT HIDROLOGI DAN KUALITAS AIR</div>
            </td>
            <td style="width: 65px; border: none;"></td>
        </tr>
    </table>

    <!-- Kotak Form Dimulai Dari Judul Form Sesuai Scan Asli -->
    <div class="form-box">
        <!-- Judul Form -->
        <div class="title-bar">
            FORM KELUAR ALAT / BARANG
        </div>

        <!-- Tabel Rincian Item -->
        <table class="content-table">
            <thead>
                <tr>
                    <th style="width: 35px;">No</th>
                    <th>Nama Alat</th>
                    <th style="width: 75px;">Jumlah</th>
                    <th style="width: 100px;">Kondisi</th>
                    <th style="width: 140px;">Ket</th>
                </tr>
            </thead>
            <tbody>
                <!-- Item Transaksi -->
                <tr>
                    <td class="text-center">1</td>
                    <td>
                        <strong>{{ $transaksi->barang->nama_barang ?? '-' }}</strong>
                        @if($transaksi->barang->merk_tipe)
                            <br><small style="color: #444;">Merk/Tipe: {{ $transaksi->barang->merk_tipe }}</small>
                        @endif
                        @if($transaksi->barang->kode_barang)
                            <br><small style="color: #666;">Kode: {{ $transaksi->barang->kode_barang }}</small>
                        @endif
                    </td>
                    <td class="text-center">
                        {{ $transaksi->jumlah }} {{ $transaksi->barang->satuan ?? 'Unit' }}
                    </td>
                    <td class="text-center">
                        {{ $transaksi->barang->kondisi ?? 'Baik' }}
                    </td>
                    <td>
                        {{ $transaksi->keterangan ?? '-' }}
                    </td>
                </tr>

                <!-- Baris Kosong Tambahan Sesuai Format Kertas Fisik (12 Baris) -->
                @for ($i = 2; $i <= 12; $i++)
                <tr>
                    <td class="text-center" style="color: #bbb;">{{ $i }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @endfor
            </tbody>
        </table>

        <!-- Catatan Keperluan (Notes) -->
        <div class="notes-box">
            <div class="notes-label">Notes : (kepentingan / keperluan)</div>
            <div>
                {{ $transaksi->keperluan ?: ($transaksi->keterangan ?: 'Untuk keperluan dinas lapangan dan operasional Unit Hidrologi & Kualitas Air.') }}
            </div>
            @if($transaksi->bagian || $transaksi->penerima)
                <div style="margin-top: 4px; font-size: 9pt; color: #444;">
                    Bagian/Tujuan: {{ $transaksi->bagian }} | Penerima: {{ $transaksi->penerima }}
                </div>
            @endif
        </div>

        <!-- Bagian Pengesahan / Tanda Tangan Sesuai Kolom Scan Fisik -->
        <div class="signature-section">
            <div class="date-line">
                Makassar, {{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y') }}
            </div>
            <table class="signature-table">
                <tr>
                    <td class="sign-header">Dibawa oleh</td>
                    <td class="sign-header">Diperiksa oleh</td>
                    <td class="sign-header">Disetujui oleh</td>
                </tr>
                <tr>
                    <td class="sign-body">
                        <div class="sign-space"></div>
                        <strong>( {{ $transaksi->dibawa_oleh ?: ($transaksi->penerima ?: '................................') }} )</strong>
                    </td>
                    <td class="sign-body">
                        <div class="sign-space"></div>
                        <strong>( {{ $transaksi->diperiksa_oleh ?: '................................' }} )</strong>
                    </td>
                    <td class="sign-body">
                        <div class="sign-space"></div>
                        <strong>( {{ $transaksi->disetujui_oleh ?: 'A. Zafriyadi, S.T.' }} )</strong>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
