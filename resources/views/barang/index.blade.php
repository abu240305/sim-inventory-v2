@extends('layouts/layoutMaster')

@section('title', 'Master Barang')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Barang</h5>
    <a href="{{ route('barang.create') }}" class="btn btn-primary">Tambah Barang</a>
  </div>
  <div class="card-body">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Kondisi</th>
            <th>Lokasi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($barangs as $barang)
            <tr>
                <td>{{ $barang->kode_barang }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                <td>
                    @if($barang->jumlah < 5)
                        <span class="badge bg-label-danger">{{ $barang->jumlah }}</span>
                    @else
                        {{ $barang->jumlah }}
                    @endif
                </td>
                <td>{{ $barang->kondisi }}</td>
                <td>{{ $barang->lokasi }}</td>
                <td>
                    <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-info">Edit</a>
                    <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
