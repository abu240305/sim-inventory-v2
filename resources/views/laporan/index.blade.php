@extends('layouts/layoutMaster')

@section('title', 'Laporan Inventaris')

@section('content')
<div class="card mb-4">
  <div class="card-header">
    <h5 class="mb-0">Filter Laporan</h5>
  </div>
  <div class="card-body">
    <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 align-items-end">
      
      <div class="col-md-3">
        <label class="form-label" for="jenis">Jenis Laporan</label>
        <select id="jenis" name="jenis" class="form-select">
          <option value="stok" {{ $jenis == 'stok' ? 'selected' : '' }}>Laporan Stok Barang (Semua)</option>
          <option value="masuk" {{ $jenis == 'masuk' ? 'selected' : '' }}>Laporan Barang Masuk</option>
          <option value="keluar" {{ $jenis == 'keluar' ? 'selected' : '' }}>Laporan Barang Keluar</option>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label" for="tanggal_mulai">Tanggal Mulai</label>
        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggal_mulai }}" />
      </div>

      <div class="col-md-3">
        <label class="form-label" for="tanggal_akhir">Tanggal Akhir</label>
        <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggal_akhir }}" />
      </div>

      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Tampilkan</button>
        
        <button type="submit" formaction="{{ route('laporan.export.pdf') }}" class="btn btn-danger" title="Export PDF">
          <i class="ri-file-pdf-line"></i> PDF
        </button>
        
        <button type="submit" formaction="{{ route('laporan.export.excel') }}" class="btn btn-success" title="Export Excel">
          <i class="ri-file-excel-line"></i> Excel
        </button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="mb-0">
      Hasil Laporan: 
      @if($jenis == 'stok') Stok Barang @elseif($jenis == 'masuk') Barang Masuk @else Barang Keluar @endif
    </h5>
    @if($jenis != 'stok')
        <small class="text-muted">Periode: {{ \Carbon\Carbon::parse($tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}</small>
    @endif
  </div>
  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead class="table-light">
          @if($jenis == 'masuk')
            <tr>
              <th>Tanggal</th>
              <th>Barang</th>
              <th>Jumlah</th>
              <th>Sumber</th>
              <th>Keterangan</th>
              <th>Petugas</th>
            </tr>
          @elseif($jenis == 'keluar')
            <tr>
              <th>Tanggal</th>
              <th>Barang</th>
              <th>Jumlah</th>
              <th>Penerima</th>
              <th>Bagian</th>
              <th>Keterangan</th>
            </tr>
          @else
            <tr>
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
          @forelse($data as $row)
            @if($jenis == 'masuk')
              <tr>
                <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $row->barang->nama_barang ?? '-' }} ({{ $row->barang->kode_barang ?? '-' }})</td>
                <td>{{ $row->jumlah }}</td>
                <td>{{ $row->sumber }}</td>
                <td>{{ $row->keterangan }}</td>
                <td>{{ $row->user->name ?? '-' }}</td>
              </tr>
            @elseif($jenis == 'keluar')
              <tr>
                <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $row->barang->nama_barang ?? '-' }} ({{ $row->barang->kode_barang ?? '-' }})</td>
                <td>{{ $row->jumlah }}</td>
                <td>{{ $row->penerima }}</td>
                <td>{{ $row->bagian }}</td>
                <td>{{ $row->keterangan }}</td>
              </tr>
            @else
              <tr>
                <td>{{ $row->kode_barang }}</td>
                <td>{{ $row->nama_barang }}</td>
                <td>{{ $row->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $row->jumlah }}</td>
                <td>{{ $row->kondisi }}</td>
                <td>{{ $row->lokasi }}</td>
              </tr>
            @endif
          @empty
            <tr>
              <td colspan="7" class="text-center">Tidak ada data untuk periode ini</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
