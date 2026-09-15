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
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <p class="title">
            @if($jenis == 'stok') Laporan Stok Barang
            @elseif($jenis == 'masuk') Laporan Transaksi Barang Masuk
            @else Laporan Transaksi Barang Keluar
            @endif
        </p>
        @if($jenis != 'stok')
            <p class="subtitle">Periode: {{ \Carbon\Carbon::parse($tanggal_mulai)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}</p>
        @else
            <p class="subtitle">Per Tanggal: {{ date('d/m/Y H:i') }}</p>
        @endif
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
