@extends('layouts/layoutMaster')

@section('title', 'Transaksi Barang Masuk')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Barang Masuk</h5>
    <a href="{{ route('transaksi-masuk.create') }}" class="btn btn-primary">Tambah Transaksi</a>
  </div>
  <div class="card-body">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Barang</th>
            <th>Jumlah</th>
            <th>Sumber</th>
            <th>Keterangan</th>
            <th>Petugas</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($transaksis as $transaksi)
            <tr>
                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $transaksi->barang->nama_barang ?? '-' }} ({{ $transaksi->barang->kode_barang ?? '-' }})</td>
                <td><span class="badge bg-label-success">+{{ $transaksi->jumlah }}</span></td>
                <td>{{ $transaksi->sumber }}</td>
                <td>{{ $transaksi->keterangan }}</td>
                <td>{{ $transaksi->user->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
