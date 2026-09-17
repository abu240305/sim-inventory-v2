<!DOCTYPE html>
<html>
<head>
    <title>Laporan Inventaris</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .title { font-size: 16px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .subtitle { font-size: 12px; margin-top: 5px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <table style="width: 100%; border: none; margin-top: 0; padding: 0;">
            <tr style="border: none;">
                <td style="border: none; width: 15%; text-align: center; padding: 0;">
                    @php
                        $appLogo = get_setting('app_logo');
                        $path = $appLogo ? public_path('storage/' . $appLogo) : null;
                        $base64 = '';
                        if ($path && file_exists($path)) {
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $imgData = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imgData);
                        }
                    @endphp
                    @if($base64)
                        <img src="{{ $base64 }}" alt="Logo Aplikasi" style="width: 70px; height: auto;">
                    @endif
                </td>
                <td style="border: none; width: 85%; text-align: center; padding: 0; padding-right: 15%;">
                    <p class="title" style="margin-bottom: 5px; font-size: 18px;">
                        @if($jenis == 'stok') LAPORAN STOK BARANG
                        @elseif($jenis == 'masuk') LAPORAN TRANSAKSI BARANG MASUK
                        @else LAPORAN TRANSAKSI BARANG KELUAR
                        @endif
                    </p>
                    @if($jenis != 'stok')
                        <p class="subtitle">Periode: {{ \Carbon\Carbon::parse($tanggal_mulai)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}</p>
                    @else
                        <p class="subtitle">Per Tanggal: {{ date('d/m/Y H:i') }}</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
          @if($jenis == 'masuk')
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>Kode Barang</th>
              <th>Nama Barang</th>
              <th>Jumlah</th>
              <th>Sumber</th>
              <th>Petugas</th>
            </tr>
          @elseif($jenis == 'keluar')
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>Kode Barang</th>
              <th>Nama Barang</th>
              <th>Jumlah</th>
              <th>Penerima</th>
              <th>Bagian</th>
            </tr>
          @else
            <tr>
              <th>No</th>
              <th>Kode Barang</th>
              <th>Nama Barang</th>
              <th>Kategori</th>
              <th>Jumlah Stok</th>
              <th>Kondisi</th>
              <th>Lokasi</th>
            </tr>
          @endif
        </thead>
        <tbody>
          @forelse($data as $index => $row)
            @if($jenis == 'masuk')
              <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $row->barang->kode_barang ?? '-' }}</td>
                <td>{{ $row->barang->nama_barang ?? '-' }}</td>
                <td class="text-center">{{ $row->jumlah }}</td>
                <td>{{ $row->sumber }}</td>
                <td>{{ $row->user->name ?? '-' }}</td>
              </tr>
            @elseif($jenis == 'keluar')
              <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $row->barang->kode_barang ?? '-' }}</td>
                <td>{{ $row->barang->nama_barang ?? '-' }}</td>
                <td class="text-center">{{ $row->jumlah }}</td>
                <td>{{ $row->penerima }}</td>
                <td>{{ $row->bagian }}</td>
              </tr>
            @else
              <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $row->kode_barang }}</td>
                <td>{{ $row->nama_barang }}</td>
                <td>{{ $row->kategori->nama_kategori ?? '-' }}</td>
                <td class="text-center">{{ $row->jumlah }}</td>
                <td>{{ $row->kondisi }}</td>
                <td>{{ $row->lokasi }}</td>
              </tr>
            @endif
          @empty
            <tr>
              <td colspan="7" class="text-center">Tidak ada data untuk laporan ini</td>
            </tr>
          @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}<br>Oleh: {{ auth()->user()->name ?? 'System' }}</p>
    </div>

</body>
</html>
