@extends('layouts/layoutMaster')

@section('title', 'Transaksi Barang Keluar')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Barang Keluar</h5>
    <a href="{{ route('transaksi-keluar.create') }}" class="btn btn-primary">Tambah Transaksi</a>
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
            <th>Penerima</th>
            <th>Bagian</th>
            <th>Keterangan</th>
            <th>Petugas</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($transaksis as $transaksi)
            <tr>
                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $transaksi->barang->nama_barang ?? '-' }} ({{ $transaksi->barang->kode_barang ?? '-' }})</td>
                <td><span class="badge bg-label-danger">-{{ $transaksi->jumlah }}</span></td>
                <td>{{ $transaksi->penerima }}</td>
                <td>{{ $transaksi->bagian }}</td>
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
