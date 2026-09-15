@extends('layouts/layoutMaster')

@section('title', 'Edit Kategori')

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Kategori</h5>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('kategori.update', $kategori->id) }}">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label class="form-label" for="nama_kategori">Nama Kategori</label>
        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="{{ $kategori->nama_kategori }}" required />
        @error('nama_kategori')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection
