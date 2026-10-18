<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Peminjaman Alat - {{ $peminjaman->nomor_surat }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm 15mm 20mm;
        }
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.45;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
        }
        .header-table td {
            vertical-align: middle;
            padding: 0;
        }
        .logo-box {
            width: 58px;
            height: 58px;
            border: 1px solid #777;
            display: block;
        }
        .instansi-title {
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            letter-spacing: 0.3px;
            line-height: 1.2;
        }
        .instansi-dir {
            font-size: 11pt;
            font-weight: bold;
            margin: 2px 0 0 0;
            line-height: 1.2;
        }
        .instansi-bbws {
            font-size: 10pt;
            font-weight: bold;
            margin: 2px 0 0 0;
            line-height: 1.2;
        }
        .instansi-address {
            font-size: 6.8pt;
            letter-spacing: -0.1px;
            margin-top: 3px;
            color: #000;
            line-height: 1.2;
            white-space: nowrap;
        }
        .solid-divider {
            border-bottom: 1.5px solid #000;
            margin-bottom: 16px;
            margin-top: 4px;
        }
        .doc-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .doc-number {
            text-align: center;
            font-size: 10pt;
            margin: 4px 0 16px 0;
        }
        .dotted-line {
            border-bottom: 1px dotted #000;
            padding-bottom: 1px;
        }
        .party-block {
            margin-bottom: 10px;
        }
        .party-heading {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 2px;
        }
        .party-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .party-table td {
            padding: 1.5px 0;
            vertical-align: top;
            font-size: 10pt;
        }
        .party-table .col-label {
            width: 85px;
        }
        .party-table .col-colon {
            width: 15px;
            text-align: center;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 14px 0;
        }
        .item-table th, .item-table td {
            border: 1px solid #000;
            padding: 4px 8px;
            font-size: 9.5pt;
        }
        .item-table th {
            text-align: center;
            font-weight: bold;
            background-color: transparent;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .paragraph {
            margin-top: 0;
            margin-bottom: 10px;
            text-align: justify;
            line-height: 1.45;
        }
        .page-break {
            page-break-before: always;
        }
        .sign-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        .sign-table td {
            width: 48%;
            vertical-align: top;
            padding: 0;
            font-size: 10pt;
        }
        .sign-space {
            height: 60px;
        }
        .return-section {
            margin-top: 95px;
            font-size: 10pt;
        }
        .return-text {
            margin-bottom: 18px;
            line-height: 1.45;
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

    <!-- ==================== HALAMAN 1 ==================== -->
    <div class="page-1">
        <!-- Kop Surat Resmi BBWS-PJ Sesuai Standar Cetak Fisik -->
        <table class="header-table">
            <tr>
                <td style="width: 75px; vertical-align: middle;">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo PU" class="logo-box">
                    @else
                        <strong>PU</strong>
                    @endif
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <div class="instansi-title">KEMENTERIAN PEKERJAAN UMUM</div>
                    <div class="instansi-dir">DIREKTORAT JENDERAL SUMBER DAYA AIR</div>
                    <div class="instansi-bbws">BALAI BESAR WILAYAH SUNGAI POMPENGAN JENEBERANG</div>
                    <div class="instansi-address">
                        Jalan Bendungan Bili-Bili No. 3, Kel. Karunrung, Kec. Rappocini, Kota Makassar 90222, Telepon (0411) 868792, Email: bbws.pompenganjeneberang@pu.go.id
                    </div>
                </td>
                <td style="width: 75px;"></td>
            </tr>
        </table>

        <!-- Garis Pembatas Kop (Tunggal Sesuai Scan) -->
        <div class="solid-divider"></div>

        <!-- Judul & Nomor Berita Acara -->
        <div class="doc-title">BERITA ACARA PEMINJAMAN ALAT</div>
        <div class="doc-number">
            Nomor : <span class="dotted-line">&nbsp;&nbsp;{{ $peminjaman->nomor_surat }}&nbsp;&nbsp;</span>
        </div>

        <!-- Teks Pembuka -->
        <p class="paragraph">
            Pada hari ini <strong>{{ $peminjaman->hari_surat }}</strong> tanggal <strong>{{ $tanggalTerbilang['tanggal'] }}</strong> bulan <strong>{{ $tanggalTerbilang['bulan'] }}</strong> tahun <strong>{{ $tanggalTerbilang['tahun'] }}</strong>, yang bertanda tangan di bawah ini:
        </p>

        <!-- Pihak Pertama -->
        <div class="party-block">
            <div class="party-heading">1. Pihak Pertama (Yang Menyerahkan Alat)</div>
            <table class="party-table">
                <tr>
                    <td class="col-label">Nama</td>
                    <td class="col-colon">:</td>
                    <td>{{ $peminjaman->nama_penyerah }}</td>
                </tr>
                <tr>
                    <td class="col-label">Jabatan</td>
                    <td class="col-colon">:</td>
                    <td>{{ $peminjaman->jabatan_penyerah }}</td>
                </tr>
                <tr>
                    <td class="col-label">Instansi</td>
                    <td class="col-colon">:</td>
                    <td>{{ $peminjaman->instansi_penyerah }}</td>
                </tr>
            </table>
        </div>

        <!-- Pihak Kedua -->
        <div class="party-block">
            <div class="party-heading">2. Pihak Kedua (Peminjam Alat)</div>
            <table class="party-table">
                <tr>
                    <td class="col-label">Nama</td>
                    <td class="col-colon">:</td>
                    <td>{{ $peminjaman->nama_peminjam }}</td>
                </tr>
                <tr>
                    <td class="col-label">Jabatan</td>
                    <td class="col-colon">:</td>
                    <td>{{ $peminjaman->jabatan_peminjam }}</td>
                </tr>
                <tr>
                    <td class="col-label">Instansi</td>
                    <td class="col-colon">:</td>
                    <td>{{ $peminjaman->instansi_peminjam }}</td>
                </tr>
            </table>
        </div>

        <!-- Kalimat Pengantar Rincian -->
        <p class="paragraph" style="margin-top: 10px; margin-bottom: 6px;">
            Dengan ini menyatakan bahwa Pihak Pertama telah menyerahkan alat kepada Pihak Kedua untuk dipergunakan sebagaimana mestinya dengan rincian sebagai berikut:
        </p>

        <!-- Tabel Rincian Alat (Sesuai Scan Bersih Tanpa Sub-tipe) -->
        <table class="item-table">
            <thead>
                <tr>
                    <th style="width: 35px;">No</th>
                    <th class="text-left">Nama Alat</th>
                    <th style="width: 65px;">Jumlah</th>
                    <th style="width: 80px;">Kondisi</th>
                    <th class="text-left" style="width: 130px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman->details as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left">{{ $detail->barang->nama_barang ?? '-' }}</td>
                    <td class="text-center">{{ $detail->jumlah }}</td>
                    <td class="text-center">{{ $detail->kondisi_pinjam ?? 'Baik' }}</td>
                    <td class="text-left">{{ $detail->keterangan ?: '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paragraf Keperluan -->
        <p class="paragraph" style="margin-bottom: 10px;">
            Alat tersebut dipinjam untuk keperluan:<br>
            {{ $peminjaman->keperluan }}
        </p>

        <!-- Paragraf Waktu Peminjaman -->
        <p class="paragraph" style="margin-bottom: 10px;">
            Waktu peminjaman alat terhitung mulai tanggal {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d F Y') }} sampai dengan tanggal {{ \Carbon\Carbon::parse($peminjaman->rencana_kembali)->translatedFormat('d F Y') }}.
        </p>

        <!-- Paragraf Tanggung Jawab -->
        <p class="paragraph" style="margin-bottom: 0;">
            Pihak Kedua bertanggung jawab atas penggunaan serta keamanan alat yang dipinjam dan wajib mengembalikannya dalam kondisi baik seperti semula.
        </p>
    </div>

    <!-- ==================== HALAMAN 2 ==================== -->
    <div class="page-break"></div>

    <div class="page-2">
        <!-- Paragraf Penutup di Bagian Atas Halaman 2 -->
        <p class="paragraph" style="margin-top: 5px; margin-bottom: 25px;">
            Demikian berita acara peminjaman alat ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.
        </p>

        <!-- Tanda Tangan Peminjaman (Kolom Kanan Berisi Tanggal Surat) -->
        <table class="sign-table">
            <tr>
                <td>
                    <div style="height: 18px; margin-bottom: 4px;"></div>
                    <div>Pihak Pertama</div>
                    <div class="sign-space"></div>
                    <div>({{ $peminjaman->nama_penyerah }})</div>
                </td>
                <td style="width: 4%;"></td>
                <td>
                    <div style="height: 18px; margin-bottom: 4px;">
                        Makassar, {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}
                    </div>
                    <div>Pihak Kedua</div>
                    <div class="sign-space"></div>
                    <div>({{ $peminjaman->nama_peminjam }})</div>
                </td>
            </tr>
        </table>

        <!-- Lembar Verifikasi Pengembalian Alat (Diisi Saat Kembali) -->
        <div class="return-section">
            <div class="return-text">
                @if($peminjaman->status === 'dikembalikan')
                    - Sudah dikembalikan pada hari {{ $peminjaman->hari_kembali }} tgl {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_aktual)->translatedFormat('d F Y') }}.<br>
                    &nbsp;&nbsp;dalam kondisi {{ strtolower($peminjaman->kondisi_kembali ?? 'baik') }}.
                @else
                    - Sudah dikembalikan pada hari ........................... tgl ..........................................<br>
                    &nbsp;&nbsp;dalam kondisi ..........................
                @endif
            </div>

            <table class="sign-table" style="margin-top: 10px;">
                <tr>
                    <td>
                        <div>yang menyerahkan .</div>
                        <div class="sign-space"></div>
                        <div>
                            @if($peminjaman->status === 'dikembalikan')
                                {{ $peminjaman->nama_penyerah_kembali ?: $peminjaman->nama_peminjam }}
                            @else
                                ..........................................
                            @endif
                        </div>
                    </td>
                    <td style="width: 4%;"></td>
                    <td>
                        <div>yang menerima .</div>
                        <div class="sign-space"></div>
                        <div>
                            @if($peminjaman->status === 'dikembalikan')
                                {{ $peminjaman->nama_penerima_kembali ?: 'A. Zafriyadi' }}
                            @else
                                ..........................................
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
