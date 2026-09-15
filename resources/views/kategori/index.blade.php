@extends('layouts/layoutMaster')

@section('title', 'Master Kategori')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Kategori</h5>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary">Tambah Kategori</a>
  </div>
  <div class="card-body">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($kategoris as $kategori)
            <tr>
                <td>{{ $kategori->id }}</td>
                <td>{{ $kategori->nama_kategori }}</td>
                <td>
                    <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-sm btn-info">Edit</a>
                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="d-inline">
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
